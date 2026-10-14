<?php
	$json = file_get_contents('php://input');

	if (!$json) {
		telegram_log('returned','','');
		return;
	}

	$data = (array)json_decode($json, TRUE);

    $MESSAGE_ID = isset( $data['message']['message_id'] ) ? $data['message']['message_id'] : null;
    if ( $MESSAGE_ID && ( $MESSAGE_ID == get_option('wp_telegram_last_id') ) ) {
        telegram_log('EXCEPTION', 'MESSAGE_ID', json_encode($json, TRUE));
        die();
    }
    update_option('wp_telegram_last_id', $MESSAGE_ID);

	$CHAT_TYPE = isset( $data['message']['chat']['type'] ) ? $data['message']['chat']['type'] : '';
	$TEXT = isset( $data['message']['text'] ) ? $data['message']['text'] : '';

	if ( $CHAT_TYPE == 'private' ) {
		$USERID = $data['message']['from']['id'];
		$FROM = $data['message']['from'];
		$CPT = 'telegram_subscribers';
		$PRIVATE = true; $GROUP = false;
		$COMMAND = $TEXT;
	} else if ( $CHAT_TYPE == 'group' || $CHAT_TYPE == 'supergroup' ) {
		$USERID = $data['message']['chat']['id'];
		$CPT = 'telegram_groups';
		$GROUP = true; $PRIVATE = false;
		$COMMAND = $TEXT;
	} else if ( !empty( $data['callback_query']['message']['text'] ) ) {
		$USERID = $data['callback_query']['message']['chat']['id'];
		$FROM = isset( $data['callback_query']['from'] ) ? $data['callback_query']['from'] : array();
		$CPT = 'telegram_subscribers';
		$PRIVATE = true; $GROUP = false;
		$COMMAND = isset( $data['callback_query']['data'] ) ? $data['callback_query']['data'] : '';
	} else {
		telegram_log('EXCEPTION', 'CHAT TYPE', json_encode($json, TRUE));
		die();
	}

	telegram_log('>>>>', $USERID, $COMMAND);

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
			update_post_meta($p, 'telegram_first_name', sanitize_text_field( isset( $FROM['first_name'] ) ? $FROM['first_name'] : '' ));
			update_post_meta($p, 'telegram_last_name', sanitize_text_field( isset( $FROM['last_name'] ) ? $FROM['last_name'] : '' ));
			update_post_meta($p, 'telegram_username', sanitize_text_field( isset( $FROM['username'] ) ? $FROM['username'] : '' ));
			telegram_sendmessage( $USERID, telegram_option('wmuser') );
		} else if ( $GROUP ) {
			update_post_meta($p, 'telegram_name', sanitize_text_field($data['message']['chat']['title']));
			telegram_log('', '', 'Bot added to <strong>'.esc_html($data['message']['chat']['title']).'</strong>');
            telegram_sendmessage( $USERID, telegram_option('wmgroup') );
		}
		return;
	} else if ($PRIVATE) {
		$POST_ID = telegram_getid( $USERID );
		$fieldsToCheck = [ 'first_name', 'last_name', 'username' ];
		foreach( $fieldsToCheck as $field ) {
			$value = isset( $FROM[$field] ) ? $FROM[$field] : '';
			if ( get_post_meta( $POST_ID, 'telegram_'.$field, true ) != $value ) {
				update_post_meta( $POST_ID, 'telegram_'.$field, sanitize_text_field($value));
			}
		}
		$counter = get_post_meta($POST_ID, 'telegram_counter', true);
		$counter = $counter ? ++$counter : 1;
		update_post_meta( $POST_ID, 'telegram_counter', $counter );
	} else if ($GROUP) {
		if ( isset( $data['message']['chat']['title'] ) ) {
			update_post_meta( telegram_getid( $USERID ), 'telegram_name', sanitize_text_field($data['message']['chat']['title']));
		}
	}

    if ( isset( $data['message']['location'] ) ) {
        $page = telegram_get_post_by_title( 'telegram-location', 'telegram_commands' );
        update_post_meta( telegram_getid( $USERID ), 'telegram_last_latitude', $data['message']['location']['latitude']);
        update_post_meta( telegram_getid( $USERID ), 'telegram_last_longitude', $data['message']['location']['longitude']);
        if ( $page ) {
            telegram_sendmessage( $USERID, $page->ID );
        }
        do_action( 'telegram_parse_location', $USERID, $data['message']['location']['latitude'], $data['message']['location']['longitude']);
        return;
    } else if ( isset( $data['message']['photo'] ) ) {
		do_action( 'telegram_parse_photo', $USERID, $data['message']['photo'] );
		return;
	} else if ( isset( $data['message']['document'] ) ) {
		do_action( 'telegram_parse_document', $USERID, $data['message']['document'] );
		return;
	}

    do_action( 'telegram_parse', $USERID, $COMMAND ); //EXPERIMENTAL

    $ok_found = false;
    if ( $COMMAND != '' ) {
        $commands = get_posts( array( 'post_type' => 'telegram_commands', 'numberposts' => -1 ) );
        foreach ( $commands as $command ):
            $lowertitle = strtolower(  get_the_title( $command ) );
            $lowermessage = strtolower( $COMMAND );
            if (
                ( $lowertitle == $lowermessage )
                ||
                ( strpos( $lowermessage, $lowertitle.' ' ) === 0 )
                ||
                ( in_array(  $lowermessage, explode(",", $lowertitle ) ) )
               ) {
                $ok_found = true;

                if ( has_post_thumbnail( $command->ID ) ) {
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $command->ID ), 'medium', true );
                    telegram_sendphoto( $USERID, $command->ID, $image[0] );
                }
                else {
                    telegram_sendmessage( $USERID, $command->ID );
                }
            }

        endforeach;
    }

	if ( $PRIVATE ) {
		switch ($TEXT) {
			case '/stop':
                $ok_found = true;
                telegram_sendmessage( $USERID, telegram_option('bmuser') );
                wp_delete_post( telegram_getid( $USERID ) );
			    break;
			default:
			    break;
		}
	}

	if ( $GROUP && isset( $data['message']['left_chat_participant']['id'] ) && $data['message']['left_chat_participant']['id'] == current( explode(':', telegram_option('token') ) ) ) {
        wp_delete_post( telegram_getid( $USERID ) );
		telegram_log('', '', 'Bot removed from <strong>'.esc_html( isset( $data['message']['chat']['title'] ) ? $data['message']['chat']['title'] : '' ).'</strong>');
	}
    if ( $PRIVATE && !$ok_found ) {
        telegram_sendmessage( $USERID, telegram_option('emuser') );
    }
?>