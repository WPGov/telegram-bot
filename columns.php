<?php
    function t_subscribers_columns($columns) {
        $columns['first_name'] ='First Name';
        $columns['last_name'] ='Last Name';
        $columns['username'] ='Username';
        $columns['sdate'] ='Subscribe Date';
        unset($columns['cb']);
        unset($columns['date']);

        return $columns;
    }
    add_filter('manage_edit-telegram_subscribers_columns', 't_subscribers_columns');
    function t_groups_columns($columns) {
        $columns['name'] ='Group Name';
        $columns['sdate'] ='Subscribe Date';
        unset($columns['cb']);
        unset($columns['date']);
        if ( defined('WP_DEBUG') && false === WP_DEBUG) {
            unset($columns['title']);
        }
        return $columns;
    }
    add_filter('manage_edit-telegram_groups_columns', 't_groups_columns');
    add_filter('bulk_actions-edit-telegram_subscribers', '__return_empty_array');
    add_filter('bulk_actions-edit-telegram_groups', '__return_empty_array');
    add_action('manage_telegram_subscribers_posts_custom_column', 't_manage_columns', 10, 2);
    add_action('manage_telegram_groups_posts_custom_column', 't_manage_columns', 10, 2);
    function t_manage_columns($column, $post_id) {
        global $post;
        switch ($column) {
            case 'name':
                printf(get_post_meta($post_id, 'telegram_name', true));
                break;
            case 'first_name':
                printf(get_post_meta($post_id, 'telegram_first_name', true));
                break;
            case 'last_name':
                printf(get_post_meta($post_id, 'telegram_last_name', true));
                break;
            case 'username':
                printf(get_post_meta($post_id, 'telegram_username', true));
                break;
            case 'isadmin':
                break;
            case 'sdate':
                printf(get_the_date());
                break;
            default:
                break;
        }
    }
?>
