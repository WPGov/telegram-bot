<?php
add_filter('manage_edit-telegram_subscribers_columns', 'subscribers_columns');
function subscribers_columns($columns) {
    $columns = array(
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'username' => 'Username',
        'status' => 'Status',
        //'isadmin' => 'Admin',
        'sdate' => 'Subscribe'
    );
    return $columns;
}

add_filter( 'bulk_actions-edit-telegram_subscribers', '__return_empty_array' );

add_action('manage_telegram_subscribers_posts_custom_column', 'my_manage_subscriber__columns', 10, 2);
function my_manage_subscriber__columns($column, $post_id) {
    global $post;
    switch ($column) {
        case 'first_name':
            printf(get_post_meta($post_id, 'telegram_first_name', true));
            break;
        case 'last_name':
            printf(get_post_meta($post_id, 'telegram_last_name', true));
            break;
        case 'username':
            printf(get_post_meta($post_id, 'telegram_username', true));
            break;
        case 'status':
            if ( get_post_meta( $post_id, 'telegram_status', true ) ) {
                printf( '<span style="color:red;">inactive</span>' );
            } else {
                printf( '<span style="color:green;">active</span>' );
            }
            break;
        case 'isadmin':
            break;
        case 'sdate':
            printf( get_the_date() );
            break;
        default:
            break;
    }
}
?>