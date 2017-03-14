<?php
add_action('init', function() {
    $labels = array(
        'name' => _x('Subscribed users', 'Post Type General Name', 'telegram-bot'),
        'singular_name' => _x('User', 'Post Type Singular Name', 'telegram-bot'),
        'menu_name' => __('Telegram', 'telegram-bot'),
        'name_admin_bar' => __('Users', 'telegram-bot'),
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
      $args['capabilities'] = array( 'create_posts' => 'do_not_allow' );
      $args['map_meta_cap'] = false;
    }
    $args = apply_filters('telegram_subscribers_register_capabilities', $args ); 
    register_post_type('telegram_subscribers', $args);

    //TELEGRAM GROUPS
    $labels = array(
        'name' => _x('Subscribed groups', 'Post Type General Name', 'telegram-bot'),
        'singular_name' => _x('Group', 'Post Type Singular Name', 'telegram-bot'),
        'menu_name' => __('Telegram', 'telegram-bot'),
        'name_admin_bar' => __('Groups', 'telegram-bot'),
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
        $args['capabilities'] = array( 'create_posts' => 'do_not_allow' );
        $args['map_meta_cap'] = false;
    }
    $args = apply_filters('telegram_groups_register_capabilities', $args ); 
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
});

add_action('add_meta_boxes', function() {
    $screens = array(
        'telegram_commands'
    );
    foreach ($screens as $screen) {
        add_meta_box('telegram_command_extra', __('Keyboard', 'telegram-bot'), function($post)
        {
            wp_nonce_field('telegram_command_extra_save_meta_box_data', 'telegram_command_extra_nonce');
            echo '<tr valign="top"><td>';
            echo '<label for="telegram_kt">';
            _e('Standard template', 'telegram-bot');
            echo '</label> ';
            echo '<input type="text" id="telegram_kt" name="telegram_kt" placeholder="1,2,3;1,2;1,2" value="' . esc_attr(get_post_meta($post->ID, 'telegram_kt', true)) . '" style="width:100%" />';
            echo '<br><small>';
            _e('Format: <b>1,2,3;1,2;1,2</b>', 'telegram-bot');
            echo ' - <a href="https://www.botpress.org/?p=2029">'.__('Examples').'</a>';
            echo '</b></small><br><small><input type="checkbox" name="telegram_otk" id="telegram_otk" value="1"';
            checked('1', get_post_meta($post->ID, 'telegram_otk', true));
            echo ' /> <label for="telegram_otk">' . __('One-Time Keyboard', 'telegram-bot') . '</label></small>';
            echo '<br><b><center>-- OR --</center></b><br>';
            echo '<div style="float:right;">';
            echo '<a onclick="telegramDefJson()"><small>'.__('Sample json', 'telegram-bot').'</small></a>';
            echo '<script>function telegramDefJson() { document.getElementById("telegram_ikt").value = \'[[{"text":"Text 1","callback_data":"1"},{"text":"Link 1","url":"https://botpress.org"}],[{"text":"Text2","callback_data":"2"}]]\'; }</script>';
            echo ' &bull; ';
            echo '<a href="https://www.botpress.org/?p=2029"><small>'.__('Examples', 'telegram-bot').'</small></a>';
            echo '</div>';
            echo '<label for="telegram_ikt">';
            _e('Inline template (json)', 'telegram-bot');
            echo '</label> ';
            echo '<textarea name="telegram_ikt" id="telegram_ikt" rows="4" style="width: 100%;margin-top: 5px;">'.esc_attr(get_post_meta($post->ID, 'telegram_ikt', true)).'</textarea>';
            //echo '<input type="text" id="telegram_ikt" name="telegram_ikt" value="' . esc_attr(get_post_meta($post->ID, 'telegram_ikt', true)) . '" style="width:100%" />';
            
        }, $screen, 'side', 'core');
    }
});

function telegram_command_extra_save_meta_box_data($post_id) {

    // Telegram Commands
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
        if (isset($_POST['telegram_ikt'])) {
            update_post_meta($post_id, 'telegram_ikt', sanitize_text_field($_POST['telegram_ikt']));
        }
        if (isset($_POST['telegram_otk'])) {
            update_post_meta($post_id, 'telegram_otk', 1);
        } else {
            update_post_meta($post_id, 'telegram_otk', 0);
        }
    }
}
add_action('save_post', 'telegram_command_extra_save_meta_box_data');

 add_action(  'future_post', function( $ID, $post ) {

    if ( empty( $ID ) ) {
        return;
    }

    // Posts future publishing
    if ( isset($_POST['telegram_m_send'] ) ) {
        telegram_log( '', 'future', 'Post ID '.$ID.' programmed for broadcast with target '.$_POST['telegram_m_send_target']);
        update_post_meta( $ID, 'telegram_future_publish', $_POST['telegram_m_send_target'].$_POST['telegram_m_send_content'] );
        return;
    } else if ( get_post_meta( $ID, 'telegram_future_publish', true ) ) {
        delete_post_meta( $ID, 'telegram_future_publish' );
    }
    
}, 10, 2 );

add_action( 'publish_post', function( $ID, $post ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( empty( $ID ) ) {
        return;
    }

    do_action( 'telegram_send_post_before', $ID ); //EXPERIMENTAL

    if ( !isset($_POST['telegram_m_send'] ) ) {
        return;
    }

    $text =  $_POST['telegram_m_send_content'];
    $text =  str_replace( '%TITLE%', get_the_title( $ID ), $text);
    $text =  str_replace( '%LINK%', get_permalink( $ID ), $text);
    $content = apply_filters('the_content', get_post( $ID )->post_content );
    $text =  str_replace( '%EXCERPT%',  wp_trim_words( $content, $num_words = 50, $more = '...' ), $text);

    update_post_meta( $ID, 'telegram_last_sent', current_time( 'timestamp' ) );

    do_action( 'telegram_send_post', $ID, $text ); //EXPERIMENTAL
    delete_post_meta( $ID, 'telegram_future_publish' );

    switch( $_POST["telegram_m_send_target"] ) {
        case 0:
            telegram_sendmessagetoall($text);
            break;
        case 1:
            telegram_sendmessage_users($text);
            break;
        case 2:
            telegram_sendmessage_groups($text);
            break;
        case 3:
            telegram_sendmessage_groups($text);
            telegram_sendmessage_users($text);
            break;
        case 4:
            telegram_sendmessage_channel($text);
            break;
    }
}, 10, 2 );

add_action( 'publish_future_post', function ( $ID ) {
    
    if ( get_post_meta( $ID, 'telegram_future_publish', true ) ) {
        $post_meta_template = get_post_meta( $ID, 'telegram_future_publish', true);
        $text =  str_replace( '%TITLE%', get_the_title( $ID ), $post_meta_template);
        $text =  str_replace( '%LINK%', get_permalink( $ID ), $text);
        $content = apply_filters('the_content', get_post( $ID )->post_content );
        $text =  str_replace( '%EXCERPT%',  wp_trim_words( $content, $num_words = 50, $more = '...' ), $text);

        update_post_meta( $ID, 'telegram_last_sent', current_time( 'timestamp' ) );

        do_action( 'telegram_send_post', $ID, $text ); //EXPERIMENTAL
        delete_post_meta( $ID, 'telegram_future_publish' );
        
        $message = substr( $text, 1);
        switch( substr( $post_meta_template, 0, 1) ) {
            case 0:
            telegram_sendmessagetoall($message);
            break;
            case 1:
            telegram_sendmessage_users($message);
            break;
            case 2:
            telegram_sendmessage_groups($message);
            break;
            case 3:
            telegram_sendmessage_groups($message);
            telegram_sendmessage_users($message);
            break;
            case 4:
            telegram_sendmessage_channel($message);
            break;
        }
    }
}, 10, 2 );

add_action( 'post_submitbox_misc_actions', function ($post) {

    if ( get_current_screen()->post_type == 'telegram_commands' ) {
        return;
    }

    echo '<div class="misc-pub-section misc-pub-section-last">';
    
    $checked = '';
    $scheduled = '';
    $template = html_entity_decode(telegram_option('posttemplate'));
    if ( get_post_meta( $post->ID, 'telegram_future_publish', true ) ) {
        $checked = ' checked';
        $scheduled = ' - <b>'.__('scheduled').'</b>';
        $template = substr( html_entity_decode(get_post_meta( $post->ID, 'telegram_future_publish', true)), 1);
    }

    echo '
    <span id="timestamp">
        <label>
            <input type="checkbox" value="1"'.$checked.' name="telegram_m_send" id="telegram_m_send" /> '.__('Send to Telegram', 'telegram-bot').'<img src="' .plugins_url('img/telegramicon.png',__FILE__). '" style="width:16px;position:absolute;padding:0 5px;" />'.$scheduled.'
        </label>
        <div id="telegram_m">
        <textarea name="telegram_m_send_content" id="telegram_m_send_content" rows="4" style="width: 100%;margin-top: 5px;">'.$template.'</textarea>
        ';?>
        <small>
        <?php
        _e('Send to:', 'telegram-bot');
        $target = telegram_option('target');
        ?> 
        <select name="telegram_m_send_target">
            <option value="0"><?php _e('Users, Groups, Channel', 'telegram-bot'); ?></option>
            <option value="1"<?php if ($target==1 ) { echo ' selected="selected"'; } ?>><?php _e('Users', 'telegram-bot'); ?></option>
            <option value="2"<?php if ($target==2 ) { echo ' selected="selected"'; } ?>><?php _e('Groups', 'telegram-bot'); ?></option>
            <option value="3"<?php if ($target==3 ) { echo ' selected="selected"'; } ?>><?php _e('Users, Groups', 'telegram-bot'); ?></option>
            <option value="4"<?php if ($target==4 ) { echo ' selected="selected"'; } ?>><?php _e('Channel', 'telegram-bot'); ?></option>
        </select>
        </small>
        </div>
        <?php
    if ( get_post_meta( $post->ID, 'telegram_last_sent', true ) ) {
        echo '<br><small>';
        _e('Last sent on: ', 'telegram-bot');
        echo date_i18n( get_option( 'date_format' ), get_post_meta( $post->ID, 'telegram_last_sent', true ) );
        echo ' ';
        echo date_i18n( get_option( 'time_format' ), get_post_meta( $post->ID, 'telegram_last_sent', true ) );
        echo '</small>';
    }
    echo '
        <script type="text/javascript">
        jQuery(document).ready(function($){

            $( "#telegram_m" ).prop( "hidden", !$("#telegram_m_send").is(":checked") );

            $("#telegram_m_send").on("change", function() {
                $( "#telegram_m" ).prop( "hidden", !$("#telegram_m_send").is(":checked") );
            });
        });
        </script>
    
    </span></div>';

} );

?>
