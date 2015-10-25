<?php
$labels = array(
    'name' => _x('Subscribers', 'Post Type General Name', 'text_domain'),
    'singular_name' => _x('Subscriber', 'Post Type Singular Name', 'text_domain'),
    'menu_name' => __('Telegram', 'text_domain'),
    'name_admin_bar' => __('Subscribers', 'text_domain'),
    'parent_item_colon' => __('Parent Item:', 'text_domain'),
    'all_items' => __('All Items', 'text_domain'),
    'add_new_item' => __('Add New Item', 'text_domain'),
    'add_new' => __('Add New', 'text_domain'),
    'new_item' => __('New Item', 'text_domain'),
    'edit_item' => __('Edit Item', 'text_domain'),
    'update_item' => __('Update Item', 'text_domain'),
    'view_item' => __('View Item', 'text_domain'),
    'search_items' => __('Search Item', 'text_domain'),
    'not_found' => __('Not found', 'text_domain'),
    'not_found_in_trash' => __('Not found in Trash', 'text_domain')
);
$args   = array(
    'label' => __('Subscriber', 'text_domain'),
    'description' => __('Post Type Description', 'text_domain'),
    'labels' => $labels,
    'taxonomies' => array(
        'telegram_groups'
    ),
    'supports' => array(
        'title',
        'editor',
        'custom-fields'
    ),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => false,
    'show_in_nav_menus' => false,
    'can_export' => false,
    'has_archive' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => true,
    'rewrite' => false,
    'capability_type' => 'post',
    'capabilities' => array(
        'create_posts' => false
    )
);
register_post_type('telegram_subscribers', $args);
$labels = array(
    'name' => _x('Commands', 'Post Type General Name', 'text_domain'),
    'singular_name' => _x('Command', 'Post Type Singular Name', 'text_domain'),
    'menu_name' => __('Commands', 'text_domain'),
    'name_admin_bar' => __('Commands', 'text_domain'),
    'parent_item_colon' => __('Parent Item:', 'text_domain'),
    'all_items' => __('All Items', 'text_domain'),
    'add_new_item' => __('Add New Item', 'text_domain'),
    'add_new' => __('Add New', 'text_domain'),
    'new_item' => __('New Item', 'text_domain'),
    'edit_item' => __('Edit Item', 'text_domain'),
    'update_item' => __('Update Item', 'text_domain'),
    'view_item' => __('View Item', 'text_domain'),
    'search_items' => __('Search Item', 'text_domain'),
    'not_found' => __('Not found', 'text_domain'),
    'not_found_in_trash' => __('Not found in Trash', 'text_domain')
);
$args   = array(
    'label' => __('Command', 'text_domain'),
    'description' => __('Post Type Description', 'text_domain'),
    'labels' => $labels,
    'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'custom-fields'
    ),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => false,
    'show_in_nav_menus' => false,
    'can_export' => false,
    'has_archive' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => true,
    'rewrite' => false,
    'capability_type' => 'page'
);
register_post_type('telegram_commands', $args);
register_taxonomy('telegram_groups', 'telegram_subscribers', array(
    'label' => 'Gruppi',
    'hierarchical' => true
));
?>