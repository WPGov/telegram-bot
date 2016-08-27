<?php
add_action('init', 'telegram_subscribers_cpt', 0);
function telegram_subscribers_cpt()
{
    $labels = array(
        'name' => _x('Subscribers', 'Post Type General Name', 'telegram-bot'),
        'singular_name' => _x('Subscriber', 'Post Type Singular Name', 'telegram-bot'),
        'menu_name' => __('Telegram', 'telegram-bot'),
        'name_admin_bar' => __('Subscribers', 'telegram-bot'),
        'parent_item_colon' => __('Parent Item:', 'telegram-bot'),
        'all_items' => __('All Items', 'telegram-bot'),
        'add_new_item' => __('Add New Item', 'telegram-bot'),
        'add_new' => __('Add New', 'telegram-bot'),
        'new_item' => __('New Item', 'telegram-bot'),
        'edit_item' => __('Edit Item', 'telegram-bot'),
        'update_item' => __('Update Item', 'telegram-bot'),
        'view_item' => __('View Item', 'telegram-bot'),
        'search_items' => __('Search Item', 'telegram-bot'),
        'not_found' => __('Not found', 'telegram-bot'),
        'not_found_in_trash' => __('Not found in Trash', 'telegram-bot')
    );
    $args   = array(
        'label' => __('Subscriber', 'telegram-bot'),
        'description' => __('Post Type Description', 'telegram-bot'),
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
        'capability_type' => 'post'
    );
    if ( defined('WP_DEBUG') && false === WP_DEBUG) {
      array_push($args,"capabilities", array( 'create_posts' => false ));
    }
    register_post_type('telegram_subscribers', $args);

    //TELEGRAM GROUPS
    $labels = array(
        'name' => _x('Subscribers', 'Post Type General Name', 'telegram-bot'),
        'singular_name' => _x('Subscriber', 'Post Type Singular Name', 'telegram-bot'),
        'menu_name' => __('Telegram', 'telegram-bot'),
        'name_admin_bar' => __('Subscribers', 'telegram-bot'),
        'parent_item_colon' => __('Parent Item:', 'telegram-bot'),
        'all_items' => __('All Items', 'telegram-bot'),
        'add_new_item' => __('Add New Item', 'telegram-bot'),
        'add_new' => __('Add New', 'telegram-bot'),
        'new_item' => __('New Item', 'telegram-bot'),
        'edit_item' => __('Edit Item', 'telegram-bot'),
        'update_item' => __('Update Item', 'telegram-bot'),
        'view_item' => __('View Item', 'telegram-bot'),
        'search_items' => __('Search Item', 'telegram-bot'),
        'not_found' => __('Not found', 'telegram-bot'),
        'not_found_in_trash' => __('Not found in Trash', 'telegram-bot')
    );
    $args   = array(
        'label' => __('Groups', 'telegram-bot'),
        'description' => __('Post Type Description', 'telegram-bot'),
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
        'capability_type' => 'post'
    );
    if ( defined('WP_DEBUG') && false === WP_DEBUG) {
      array_push($args,"capabilities", array( 'create_posts' => false ));
    }
    register_post_type('telegram_groups', $args);
    
    $labels = array(
        'name' => _x('Commands', 'Post Type General Name', 'telegram-bot'),
        'singular_name' => _x('Command', 'Post Type Singular Name', 'telegram-bot'),
        'menu_name' => __('Commands', 'telegram-bot'),
        'name_admin_bar' => __('Commands', 'telegram-bot'),
        'parent_item_colon' => __('Parent Item:', 'telegram-bot'),
        'all_items' => __('All Commands', 'telegram-bot'),
        'add_new_item' => __('Add New Command', 'telegram-bot'),
        'add_new' => __('Add New', 'telegram-bot'),
        'new_item' => __('New Command', 'telegram-bot'),
        'edit_item' => __('Edit Command', 'telegram-bot'),
        'update_item' => __('Update Command', 'telegram-bot'),
        'view_item' => __('View Command', 'telegram-bot'),
        'search_items' => __('Search Command', 'telegram-bot'),
        'not_found' => __('Not found', 'telegram-bot'),
        'not_found_in_trash' => __('Not found in Trash', 'telegram-bot')
    );
    $args   = array(
        'label' => __('Command', 'telegram-bot'),
        'description' => __('Post Type Description', 'telegram-bot'),
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
}
function telegram_commands_meta_box()
{
    $screens = array(
        'telegram_commands'
    );
    foreach ($screens as $screen) {
        add_meta_box('telegram_command_extra', __('Extra', 'telegram-bot'), 'telegram_command_extra_callback', $screen, 'side', 'core');
    }
}
add_action('add_meta_boxes', 'telegram_commands_meta_box');
function telegram_command_extra_callback($post)
{
    // Add a nonce field so we can check for it later.
    wp_nonce_field('telegram_command_extra_save_meta_box_data', 'telegram_command_extra_nonce');
    echo '<tr valign="top"><td>';
    echo '<label for="telegram_keyboard_template">';
    _e('Keyboard Template', 'telegram_keyboard_template');
    echo '</label> ';
    echo '<input type="text" id="telegram_kt" name="telegram_kt" value="' . esc_attr(get_post_meta($post->ID, 'telegram_kt', true)) . '" size="25" />';
    echo '<br><small>Example: <b>1,2,3;4,5,6;Text</b></small><br><br><input type="checkbox" name="telegram_otk" id="telegram_otk" value="1"';
    checked('1', get_post_meta($post->ID, 'telegram_otk', true));
    echo ' /> <label for="telegram_otk">' . __('One-Time Keyboard', 'telegram-bot') . '</label>';
}
function telegram_command_extra_save_meta_box_data($post_id)
{
    if (!isset($_POST['telegram_command_extra_nonce'])) {
        return;
    }
    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['telegram_command_extra_nonce'], 'telegram_command_extra_save_meta_box_data')) {
        return;
    }
    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'telegram_commands' == $_POST['post_type'] && current_user_can('edit_post', $post_id)) {
        if (isset($_POST['telegram_kt'])) {
            update_post_meta($post_id, 'telegram_kt', sanitize_text_field($_POST['telegram_kt']));
        }
        if (isset($_POST['telegram_otk'])) {
            update_post_meta($post_id, 'telegram_otk', 1);
        } else {
            update_post_meta($post_id, 'telegram_otk', 0);
        }
    }
}
add_action('save_post', 'telegram_command_extra_save_meta_box_data');

add_action( 'post_submitbox_misc_actions', 'telegram_metabox' );
function telegram_metabox($post) {

    echo '<div class="misc-pub-section misc-pub-section-last">
         <span id="timestamp">'
         . '<label><input type="checkbox" value="1" name="telegram_m_send" id="telegram_m_send" /> '.__('Send to Telegram', 'telegram-bot').'</label>
          <textarea name="telegram_m_send_content" id="telegram_m_send_content" rows="4" style="width: 100%;font-size: 0.9em;margin-top: 5px;">'.html_entity_decode(telegram_option('posttemplate')).'
</textarea>

<script type="text/javascript">
jQuery(document).ready(function($){

	$( "#telegram_m_send_content" ).prop( "hidden", !$("#telegram_m_send").is(":checked") );

	$("#telegram_m_send").on("change", function() {
		$( "#telegram_m_send_content" ).prop( "hidden", !$("#telegram_m_send").is(":checked") );
	});
});
</script>

               </span></div>';
}

function telegram_metabox_save($postid) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
          return;

    if ( empty($postid) ) return;

    if ( isset($_POST['telegram_m_send'] ) ) {
      $text =  $_POST['telegram_m_send_content'];
      $text =  str_replace( '%TITLE%', get_the_title( $postid ), $text);
      $text =  str_replace( '%LINK%', get_permalink( $postid ), $text);
      $content = apply_filters('the_content', get_post( $postid )->post_content );
      $text =  str_replace( '%EXCERPT%',  wp_trim_words( $content, $num_words = 50, $more = '...' ), $text);

        telegram_sendmessagetoall( $text );
    }

}
add_action( 'save_post', 'telegram_metabox_save', 10, 3 );
?>
