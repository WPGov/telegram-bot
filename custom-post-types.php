<?php
add_action('init', function() {
    // TELEGRAM USERS
    $args = array(
        'label' => __('Subscriber', 'telegram-bot'),
        'description' => __('Post Type Description', 'telegram-bot'),
        'labels' => array(
            'name' => __('Users subscribed', 'telegram-bot'),
            'singular_name' => __('User',  'telegram-bot'),
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
        ),
        'taxonomies' => array(
            'telegram_groups'
        ),
        'supports' => array(
            'title',
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
        'publicly_queryable' => false,
        'rewrite' => false,
        'capability_type' => 'post'
    );
    if ( defined('WP_DEBUG') && false === WP_DEBUG) {
      $args['capabilities'] = array( 'create_posts' => 'false' );
      $args['map_meta_cap'] = true;
    }
    $args = apply_filters('telegram_subscribers_register_capabilities', $args ); 
    register_post_type('telegram_subscribers', $args);

    // TELEGRAM GROUPS
    $args = array(
        'label' => __('Groups', 'telegram-bot'),
        'description' => __('Post Type Description', 'telegram-bot'),
        'labels' => array(
            'name' => __('Groups subscribed', 'telegram-bot'),
            'singular_name' => __('Group', 'telegram-bot'),
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
        ),
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
        'publicly_queryable' => false,
        'rewrite' => false,
        'capability_type' => 'post'
    );
    if ( defined('WP_DEBUG') && false === WP_DEBUG) {
        $args['capabilities'] = array( 'create_posts' => 'do_not_allow' );
        $args['map_meta_cap'] = false;
    }
    $args = apply_filters('telegram_groups_register_capabilities', $args ); 
    register_post_type('telegram_groups', $args);
    
    // TELEGRAM COMMANDS
    $args   = array(
        'label' => __('Command', 'telegram-bot'),
        'description' => __('Post Type Description', 'telegram-bot'),
        'labels' => array(
            'name' => __('Commands and autoresponders', 'telegram-bot'),
            'singular_name' => __('Command', 'telegram-bot'),
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
        ),
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
    add_meta_box('telegram_command_extra', __('Keyboard', 'telegram-bot'), function($post) {
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
        
    }, 'telegram_commands', 'side', 'core');
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

function telegram_on_post_scheduled( $ID, $post ) {

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
    
}

function telegram_send_post_notification( $ID, $post ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( empty( $ID ) ) {
        return;
    }

    do_action( 'telegram_send_post_before', $ID ); //EXPERIMENTAL

    if ( get_post_meta( $ID, 'telegram_tosend', true ) ) { // GUTENBERG

        $text = get_post_meta( $ID, 'telegram_tosend_message', true );
        if ( !$text ) {
            $text = html_entity_decode( telegram_option('posttemplate') );
        }

        $text =  str_replace( '%TITLE%', get_the_title( $ID ), $text);
        $text =  str_replace( '%LINK%', get_permalink( $ID ), $text);
        $content = apply_filters('the_content', get_post( $ID )->post_content );
        $text =  str_replace( '%EXCERPT%',  wp_trim_words( $content, $num_words = 50, $more = '...' ), $text);
    
        update_post_meta( $ID, 'telegram_last_sent', current_time( 'timestamp' ) );
    
        do_action( 'telegram_send_post', $ID, $text ); //EXPERIMENTAL

        delete_post_meta( $ID, 'telegram_tosend' );
        delete_post_meta( $ID, 'telegram_tosend_message' );

        $send_parameters = array(
            $text,
            $reply_markup = false,
            $disable_web_page_preview = false,
            $disable_notification = false
        );

        $send_parameters = apply_filters(
            'telegram_send_post_hook',
            $send_parameters, $ID, $post
        );

        $meta_target = get_post_meta( $ID, 'telegram_tosend_target', true );
        $meta_target = ( $meta_target ? $meta_target : telegram_option('target') );
        
        switch( $meta_target ) {
            case 1:
                telegram_sendmessage_users( ...$send_parameters );
                break;
            case 2:
                telegram_sendmessage_groups( ...$send_parameters );
                break;
            case 3:
                telegram_sendmessage_groups( ...$send_parameters );
                telegram_sendmessage_users( ...$send_parameters );
                break;
            case 4:
                telegram_sendmessage_channel( ...$send_parameters );
                break;
            case 5:
                telegram_sendmessagetoall( ...$send_parameters );
                break;
        }
        delete_post_meta( $ID, 'telegram_tosend_target' );

    } else { // CLASSIC EDITOR

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
    
        $send_parameters = array(
            $text,
            $reply_markup = false,
            $disable_web_page_preview = false,
            $disable_notification = false
        );

        $send_parameters = apply_filters(
            'telegram_send_post_hook',
            $send_parameters, $ID, $post
        );

        $meta_target = $_POST["telegram_m_send_target"];
        $meta_target = ( $meta_target ? $meta_target : telegram_option('target') );

        switch( $meta_target ) {
            case 1:
                telegram_sendmessage_users( ...$send_parameters );
                break;
            case 2:
                telegram_sendmessage_groups( ...$send_parameters );
                break;
            case 3:
                telegram_sendmessage_groups( ...$send_parameters );
                telegram_sendmessage_users( ...$send_parameters );
                break;
            case 4:
                telegram_sendmessage_channel( ...$send_parameters );
                break;
            case 5:
                telegram_sendmessagetoall( ...$send_parameters );
                break;
        }
    }
}

function telegram_send_post_notification_future( $ID ) {
    
    if ( get_post_meta( $ID, 'telegram_future_publish', true ) ) {
        $post_meta_template = get_post_meta( $ID, 'telegram_future_publish', true);
        $text =  str_replace( '%TITLE%', get_the_title( $ID ), $post_meta_template);
        $text =  str_replace( '%LINK%', get_permalink( $ID ), $text);
        $content = apply_filters('the_content', get_post( $ID )->post_content );
        $text =  str_replace( '%EXCERPT%',  wp_trim_words( $content, $num_words = 50, $more = '...' ), $text);

        update_post_meta( $ID, 'telegram_last_sent', current_time( 'timestamp' ) );

        do_action( 'telegram_send_post', $ID, $text ); //EXPERIMENTAL
        delete_post_meta( $ID, 'telegram_future_publish' );
        
        $text = substr( $text, 1);

        $send_parameters = array(
            $text,
            $reply_markup = false,
            $disable_web_page_preview = false,
            $disable_notification = false
        );

        $send_parameters = apply_filters(
            'telegram_send_post_hook',
            $send_parameters, $ID, $post
        );

        switch( substr( $post_meta_template, 0, 1) ) {
            case 0:
                telegram_sendmessagetoall( ...$send_parameters );
                break;
            case 1:
                telegram_sendmessage_users( ...$send_parameters );
                break;
            case 2:
                telegram_sendmessage_groups( ...$send_parameters );
                break;
            case 3:
                telegram_sendmessage_groups( ...$send_parameters );
                telegram_sendmessage_users( ...$send_parameters );
                break;
            case 4:
                telegram_sendmessage_channel( ...$send_parameters );
                break;
        }
    }
}

add_action( 'future_post', 'telegram_on_post_scheduled', 10, 2 );
add_action( 'publish_post', 'telegram_send_post_notification', 10, 2 );
add_action( 'publish_future_post', 'telegram_send_post_notification_future', 10, 2 );

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


add_action( 'enqueue_block_editor_assets', function() {

    $screen = get_current_screen();
    if( $screen->post_type === 'telegram_commands' || !post_type_supports( $screen->post_type, 'custom-fields' )) {
        return;
    }
    
    wp_enqueue_script(
        'telegram-gutenberg',
        plugins_url( 'gutenberg.js', __FILE__ ),
        array( 'wp-i18n', 'wp-blocks', 'wp-edit-post', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-plugins', 'wp-edit-post' )
    );

    wp_localize_script(
        'telegram-gutenberg',
        'TelegramBotParams',
        array(
            'target' => ( telegram_option('target') ? telegram_option('target') : 0),
            'template' => html_entity_decode(telegram_option('posttemplate')),
            'string_localize__andsendtotelegram' => __(' and send to Telegram', 'telegram-bot' ),
            'string_localize__message' => __( 'Message', 'telegram-bot' ),
            'string_localize__preview' => __( 'Preview', 'telegram-bot' )

        ) );
} );

add_action( 'init', function(){

    register_meta( 'post', 'telegram_tosend', array(
        'type'		=> 'boolean',
        'single'	=> true,
        'show_in_rest'	=> true
    ) );

	register_meta( 'post', 'telegram_tosend_message', array(
 		'type'		=> 'string',
 		'single'	=> true,
 		'show_in_rest'	=> true
 	) );
 
	register_meta( 'post', 'telegram_tosend_target', array(
 		'type'		=> 'integer',
 		'single'	=> true,
 		'show_in_rest'	=> true
 	) ); 
});

?>
