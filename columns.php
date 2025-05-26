<?php
    
    add_filter('manage_edit-telegram_subscribers_columns', function($columns) {
        $columns['user_name'] ='Name';
        $columns['username'] ='Username';
        $columns['activity'] ='Input received';
        $columns['sdate'] ='Subscribed on';
        unset($columns['date']);
        if ( defined('WP_DEBUG') && false === WP_DEBUG) {
            unset($columns['title']);
        }
        return apply_filters( 'manage_edit-telegram_subscribers_columns_filter', $columns );
    } );

    add_filter('manage_edit-telegram_groups_columns', function($columns) {
        $columns['name'] ='Group Name';
        $columns['sdate'] ='Subscribed on';
        unset($columns['cb']);
        unset($columns['date']);
        if ( defined('WP_DEBUG') && false === WP_DEBUG) {
            unset($columns['title']);
        }
        return apply_filters( 'manage_edit-telegram_groups_columns_filter', $columns );
    } );

    add_filter('bulk_actions-edit-telegram_subscribers', function($bulk_actions) {
        $bulk_actions['telegram-send'] = __('Send message', 'telegram-bot');
        return $bulk_actions;
    });

    add_filter('handle_bulk_actions-edit-telegram_subscribers', function($redirect_url, $action, $post_ids) {
        if ($action == 'telegram-send') {
            
            $querystring = implode( ',', $post_ids);
            $redirect_url = admin_url('admin.php?page=telegram_send&telegram_post_ids='.$querystring, 'http');
        }
        return $redirect_url;
    }, 10, 3);

    #add_filter('bulk_actions-edit-telegram_subscribers', function($actions){ unset( $actions['edit'] ); return apply_filters( 'bulk_actions-edit-telegram_subscribers_filter', $actions ); });
    #add_filter('bulk_actions-edit-telegram_groups', function($actions){ unset( $actions['edit'] ); return apply_filters( 'bulk_actions-edit-telegram_groups_filter', $actions ); });

    add_action('manage_telegram_subscribers_posts_custom_column', 't_manage_columns', 10, 2);
    add_action('manage_telegram_groups_posts_custom_column', 't_manage_columns', 10, 2);
    function t_manage_columns($column, $post_id) {
        global $post;
        switch ($column) {
            case 'name':
                printf(get_post_meta($post_id, 'telegram_name', true));
                break;
            case 'user_name':
                echo '<a class="row-title" href="'.get_edit_post_link( $post_id ).'">'.get_post_meta($post_id, 'telegram_first_name', true) . ' ' . get_post_meta($post_id, 'telegram_last_name', true).'</a>';
                break;
            case 'username':
                if ( get_post_meta($post_id, 'telegram_username', true) ) {
                    echo '<code>'.get_post_meta($post_id, 'telegram_username', true).'</code>';
                }
                break;
            case 'activity':
                echo get_post_meta( $post_id, 'telegram_counter', true );
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

    add_action('admin_init', function() {
        global $typenow;
        
        if ($typenow === 'telegram_subscribers') {
            add_filter('posts_search', function($search, $query) {
                global $wpdb;
        
                if ($query->is_main_query() && !empty($query->query['s'])) {
                    $sql    = "
                    or exists (
                        select * from {$wpdb->postmeta} where post_id={$wpdb->posts}.ID
                        and meta_key in ('telegram_first_name','telegram_last_name','telegram_username')
                        and meta_value like %s
                    )
                ";
                    $like   = '%' . $wpdb->esc_like($query->query['s']) . '%';
                    $search = preg_replace("#\({$wpdb->posts}.post_title LIKE [^)]+\)\K#",
                        $wpdb->prepare($sql, $like), $search);
                }
        
                return $search;
                echo $search;
            }, 10, 2);
        }
    });

?>
