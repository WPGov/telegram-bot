<?php

	$json = file_get_contents('php://input');

	if (!$json) {
		return;
	}

	$data = (array)json_decode($json, TRUE);

    if ( $data['message']['message_id'] && ( $data['message']['message_id'] == get_option('wp_telegram_last_id') ) ) {
        telegram_log('EXCEPTION', 'MESSAGE_ID', json_encode($json, TRUE));
        die();
    }
    update_option('wp_telegram_last_id', $data['message']['message_id']);

	if ( $data['message']['chat']['type'] == 'private' ) {
		$USERID = $data['message']['from']['id'];
		$CPT = 'telegram_subscribers';
		$PRIVATE = true; $GROUP = false;
	} else if ( $data['message']['chat']['type'] == 'group' || $data['message']['chat']['type'] == 'supergroup' ) {
		$USERID = $data['message']['chat']['id'];
		$CPT = 'telegram_groups';
		$GROUP = true; $PRIVATE = false;
	} else {
		telegram_log('EXCEPTION', 'CHAT TYPE', json_encode($json, TRUE));
		die();
	}

	telegram_log('>>>>', $USERID, $data['message']['text']);

    if (defined('WP_DEBUG') && true === WP_DEBUG) {
	   telegram_log('####', 'DEBUG', json_encode($json, TRUE));
    }

	if ( !telegram_getid( $USERID ) ) {
		$p = wp_insert_post(array(
			'post_title' => $USERID,
			'post_content' => '',
			'post_type' => $CPT,
			'post_status' => 'publish',
			'post_author' => 1,
		));
		if ( $PRIVATE ) {
			update_post_meta($p, 'telegram_first_name', $data['message']['from']['first_name']);
			update_post_meta($p, 'telegram_last_name', $data['message']['from']['last_name']);
			update_post_meta($p, 'telegram_username', $data['message']['from']['username']);
			telegram_sendmessage( $USERID, telegram_option('wmuser') );
		} else if ( $GROUP ) {
			update_post_meta($p, 'telegram_name', $data['message']['chat']['title']);
			telegram_log('', '', 'Bot added to <strong>'.$data['message']['chat']['title'].'</strong>');
            telegram_sendmessage( $USERID, telegram_option('wmgroup') );
		}
		return;
	} else if ($PRIVATE) {
	    update_post_meta($o->ID, 'telegram_first_name', $data['message']['from']['first_name']);
		update_post_meta($o->ID, 'telegram_last_name', $data['message']['from']['last_name']);
		update_post_meta($o->ID, 'telegram_username', $data['message']['from']['username']);
	} else if ($GROUP) {
		update_post_meta($o->ID, 'telegram_name', $data['message']['chat']['title']);
	}

    if ( isset( $data['message']['location'] ) ) {
        $page = get_page_by_title( 'telegram-location', '', 'telegram_commands' );
        update_post_meta( telegram_getid( $USERID ), 'telegram_last_latitude', $data['message']['location']['latitude']);
        update_post_meta( telegram_getid( $USERID ), 'telegram_last_longitude', $data['message']['location']['longitude']);
        telegram_sendmessage( $USERID, $page->ID );
        do_action( 'telegram_parse_location', $USERID, $data['message']['location']['latitude'], $data['message']['location']['longitude']);
        return;
    } else if ( isset( $data['message']['photo'] ) ) {
			do_action( 'telegram_parse_photo', $USERID, $data['message']['photo'] );
			return;
		} else if ( isset( $data['message']['document'] ) ) {
			do_action( 'telegram_parse_document', $USERID, $data['message']['document'] );
			return;
		}

    do_action( 'telegram_parse', $USERID, $data['message']['text'] ); //EXPERIMENTAL

    $ok_found = false;
    if ( $data['message']['text'] != '' ) {
        query_posts('post_type=telegram_commands&posts_per_page=-1');
        while (have_posts()):
            the_post();
            $lowertitle = strtolower(  get_the_title() );
            $lowermessage = strtolower( $data['message']['text'] );
            if (
                ( $lowertitle == $lowermessage )
                ||
                ( strpos( $lowermessage, $lowertitle.' ' ) === 0 )
                ||
                ( in_array(  $lowermessage, explode(",", $lowertitle ) ) )
               ) {
                $ok_found = true;

                if ( has_post_thumbnail( get_the_id() ) ) {
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium', true );
                    telegram_sendphoto( $USERID, get_the_id(), $image[0] );
                }
                else {
                    telegram_sendmessage( $USERID, get_the_id() );
                }
            }

        endwhile;
    }

	if ( $PRIVATE ) {
		switch ($data['message']['text']) {
			case '/stop':
                $ok_found = true;
                telegram_sendmessage( $USERID, telegram_option('bmuser') );
                wp_delete_post( telegram_getid( $USERID ) );
			    break;
			default:
			    break;
			return;
		}
	}

	if ( $GROUP && $data['message']['left_chat_participant']['id'] == current( explode(':', telegram_option('token') ) ) ) {
        wp_delete_post( telegram_getid( $USERID ) );
		telegram_log('', '', 'Bot removed from <strong>'.$data['message']['chat']['title'].'</strong>');
	}
    if ( $PRIVATE && !$ok_found ) {
         telegram_sendmessage( $USERID, telegram_option('emuser') );
    }

?>
