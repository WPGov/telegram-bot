<?php
$json = file_get_contents('php://input');

if (!$json) {
	return;
}

$data = (array)json_decode($json, TRUE);
telegram_log('------>', $data['message']['from']['id'], $data['message']['text']);


$USERID = $data['message']['from']['id'];

if (!get_page_by_title( $USERID, OBJECT, 'telegram_subscribers')) {
	$p = wp_insert_post(array(
		'post_title' => $data['message']['from']['id'],
		'post_content' => '',
		'post_type' => 'telegram_subscribers',
		'post_status' => 'publish',
		'post_author' => 1,
	));
	update_post_meta($p, 'telegram_first_name', $data['message']['from']['first_name']);
	update_post_meta($p, 'telegram_last_name', $data['message']['from']['last_name']);
	update_post_meta($p, 'telegram_username', $data['message']['from']['username']);
	telegram_sendmessage( $USERID, 'Welcome, ' . $data['message']['from']['first_name'] . '!');
	return;
}

if ( telegram_useractive( $data['message']['from']['id'] ) ) {
    query_posts('post_type=telegram_commands');
    while (have_posts()):
        the_post();
        if (strtolower( get_the_title() ) == strtolower( $data['message']['text'] )) {
            if (has_post_thumbnail(get_the_id())) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium', true ); 
                telegram_sendphoto( $USERID, get_the_content(), $image[0] );
            }
            else {
                telegram_sendmessage( $USERID, get_the_content());
            }
        }

    endwhile;
}

    switch ($data['message']['text']) {
        case '/admin':
	       if ( $USERID == '%admin%') {
		      telegram_sendmessage( $USERID, 'Logged in as (admin)' . $data['message']['from']['first_name']);
	       }
            break;
        case '/start':
            if ( telegram_useractive( $USERID ) ) {
                telegram_sendmessage( $USERID, '== ALREADY STARTED ==');
            } else {
                telegram_sendmessage( $USERID, '== WELCOME ==');
                delete_post_meta( telegram_getid( $USERID ), 'telegram_status');
            }
            break;   
        case '/stop':
            if ( telegram_useractive( $USERID ) ) {
                telegram_sendmessage( $USERID, '== BYE ==');
                update_post_meta( telegram_getid( $USERID ), 'telegram_status', '1');
            } else {
                telegram_sendmessage( $USERID, '== ALREADY STOPPED ==');
            }
            break;              
        default:
            break;

	return;
}
    

?>