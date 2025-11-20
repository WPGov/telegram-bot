<?php
/*
Plugin Name:  Telegram Bot & Channel
Plugin URI:  https://wordpress.org/plugins/telegram-bot/
Description: Broadcast your content to Telegram, build interactive bots and boost your omnichannel customer experience
Version: 4.1.1
Author: Marco Milesi
Author URI: https://www.marcomilesi.com
Contributors: Milmor
Text Domain:  telegram-bot
Domain Path: /languages
*/

require_once plugin_dir_path(__FILE__) . 'columns.php';
require_once plugin_dir_path(__FILE__) . 'admin-messages.php';
require_once plugin_dir_path(__FILE__) . 'panel/send.php';

add_action( 'plugins_loaded', function(){
    load_plugin_textdomain( 'telegram-bot' );
} );

function telegram_admin_menu() {
    $icon = 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 240 240" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M222.51 19.53c-2.674.083-5.354.78-7.783 1.872-4.433 1.702-51.103 19.78-97.79 37.834C93.576 68.27 70.25 77.28 52.292 84.2 34.333 91.12 21.27 96.114 19.98 96.565c-4.28 1.502-10.448 3.905-14.582 8.76-2.066 2.428-3.617 6.794-1.804 10.53 1.812 3.74 5.303 5.804 10.244 7.69l.152.058.156.048c17.998 5.55 45.162 14.065 48.823 15.213.95 3.134 12.412 40.865 18.65 61.285 1.602 4.226 6.357 7.058 10.773 6.46.794.027 2.264.014 3.898-.378 2.383-.57 5.454-1.924 8.374-4.667l.002-.002c4.153-3.9 18.925-18.373 23.332-22.693l48.27 35.643.18.11s4.368 2.894 10.134 3.284c2.883.195 6.406-.33 9.455-2.556 3.05-2.228 5.25-5.91 6.352-10.71 3.764-16.395 29.428-138.487 33.83-158.837 2.742-10.348 1.442-18.38-3.7-22.872-2.59-2.26-5.675-3.275-8.827-3.395-.394-.015-.788-.016-1.183-.004zm.545 10.02c1.254.02 2.26.365 2.886.91 1.252 1.093 2.878 4.386.574 12.944-12.437 55.246-23.276 111.71-33.87 158.994-.73 3.168-1.752 4.323-2.505 4.873-.754.552-1.613.744-2.884.658-2.487-.17-5.36-1.72-5.488-1.79l-78.207-57.745c7.685-7.266 59.17-55.912 87.352-81.63 3.064-2.95.584-8.278-3.53-8.214-5.294 1.07-9.64 4.85-14.437 7.212-34.79 20.36-100.58 60.213-106.402 63.742-3.04-.954-30.89-9.686-49.197-15.332-2.925-1.128-3.962-2.02-4.344-2.36.007-.01.002.004.01-.005 1.362-1.6 6.97-4.646 10.277-5.807 2.503-.878 14.633-5.544 32.6-12.467 17.965-6.922 41.294-15.938 64.653-24.97 32.706-12.647 65.46-25.32 98.137-37.98 1.617-.75 3.12-1.052 4.375-1.032zM100.293 158.41l19.555 14.44c-5.433 5.32-18.327 17.937-21.924 21.322l2.37-35.762z"/></svg>');

    add_menu_page( __('Telegram Dashboard', 'telegram-bot'), __('Telegram', 'telegram-bot'), 'manage_options', 'telegram_main', 'telegram_main_page', $icon , 25 );
    add_submenu_page('telegram_main', __('Users', 'telegram-bot'), __('Users', 'telegram-bot'), 'manage_options', 'edit.php?post_type=telegram_subscribers');
    add_submenu_page('telegram_main', __('Groups', 'telegram-bot'), __('Groups', 'telegram-bot'), 'manage_options', 'edit.php?post_type=telegram_groups');
    add_submenu_page('telegram_main', __('Broadcast', 'telegram-bot'), __('Broadcast', 'telegram-bot'), 'manage_options', 'telegram_send', 'telegram_send_panel' );
    add_submenu_page('telegram_main', __('Responders', 'telegram-bot'), __('Responders', 'telegram-bot'), 'manage_options', 'edit.php?post_type=telegram_commands');
    add_submenu_page('telegram_main', __('Settings', 'telegram-bot'), __('Settings', 'telegram-bot'), 'manage_options', 'telegram_settings', 'telegram_settings_page');
    
    if ( !telegram_option('disable_log') ) {
        add_submenu_page('telegram_main', __('Log', 'telegram-bot'), __('Log', 'telegram-bot'), 'manage_options', 'telegram_log', 'telegram_log_panel');
    }
}
add_action('admin_menu', 'telegram_admin_menu');

function telegram_main_page() {
    require_once plugin_dir_path(__FILE__) . 'panel/main.php';
}

function telegram_settings_page() {
    require_once plugin_dir_path(__FILE__) . 'panel/settings.php';
}

function telegram_log_panel() {
    if (isset($_GET['tbclear'])) {
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'telegram_log_clear')) {
            wp_die(__('Security check failed.','telegram-bot'));
        }
        delete_option('wp_telegram_log');
    }
    $log = get_option('wp_telegram_log');
    echo '<div class="wrap telegram-log-panel">';
    echo '<h1 style="margin-bottom:24px;">'.__('Activity Log', 'telegram-bot').'</h1>';
    echo '<div style="margin-bottom:20px;">';
    echo '<a href="admin.php?page=telegram_log" class="button button-secondary" style="margin-right:10px;">'.__('Reload', 'telegram-bot').'</a>';
    echo '<a href="'.wp_nonce_url('admin.php?page=telegram_log&tbclear=1', 'telegram_log_clear').'" class="button button-danger" style="color:#fff;background:#d63638;border-color:#d63638;">'.__('Clear Log', 'telegram-bot').'</a>';
    echo '</div>';
    echo '<div style="overflow-x:auto;">';
    echo '<table class="widefat fixed striped" cellspacing="0" style="min-width:700px;">';
    echo '<thead><tr>';
    echo '<th style="width: 7%;">'.__('Type', 'telegram-bot').'</th>';
    echo '<th style="width: 15%;">'.__('Date', 'telegram-bot').'</th>';
    echo '<th style="width: 15%;">'.__('Author', 'telegram-bot').'</th>';
    echo '<th>'.__('Description', 'telegram-bot').'</th>';
    echo '</tr></thead><tbody>';
    if ( is_array( $log ) && count($log) ) {
        foreach ( $log as $line ) {
            echo '<tr>';
            echo '<td><span style="font-weight:bold;color:#2271b1;">' . ( isset( $line[0] ) ? esc_html($line[0]) : '' ) . '</span></td>';
            echo '<td>' . ( isset( $line[1] ) ? esc_html($line[1]) : '' ) . '</td>';
            echo '<td>' . ( isset( $line[2] ) ? esc_html($line[2]) : '' ) . '</td>';
            echo '<td>' . ( isset( $line[3] ) ? esc_html($line[3]) : '' ) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4" style="text-align:center;color:#888;">'.__('No log entries found.', 'telegram-bot').'</td></tr>';
    }
    echo '</tbody></table></div>';
    echo '<style>.telegram-log-panel .button-danger:hover{background:#a00!important;border-color:#a00!important;}</style>';
    echo '</div>';
}

add_action('admin_init', function() {
    register_setting('wp_telegram_options', 'wp_telegram', 'sanitize_telegram_options');

    $arraytbpv = get_plugin_data ( __FILE__ );
    $nuova_versione = $arraytbpv['Version'];

    if ( version_compare( get_option('wp_telegram_version'), $nuova_versione, '<')) {
        telegram_defaults();
        update_option( 'wp_telegram_version', $nuova_versione );
    }
});

function sanitize_telegram_options($input) {
    $output = array();
    if (!is_array($input)) return $output;
    // token: keep as raw string but trim
    if (isset($input['token'])) $output['token'] = sanitize_text_field(trim($input['token']));
    if (isset($input['username'])) $output['username'] = sanitize_text_field(trim($input['username']));
    if (isset($input['channelusername'])) $output['channelusername'] = sanitize_text_field(trim($input['channelusername']));
    if (isset($input['posttemplate'])) $output['posttemplate'] = wp_kses_post($input['posttemplate']);
    if (isset($input['wmuser'])) $output['wmuser'] = sanitize_text_field($input['wmuser']);
    if (isset($input['bmuser'])) $output['bmuser'] = sanitize_text_field($input['bmuser']);
    if (isset($input['emuser'])) $output['emuser'] = sanitize_text_field($input['emuser']);
    if (isset($input['wmgroup'])) $output['wmgroup'] = sanitize_text_field($input['wmgroup']);
    if (isset($input['keyboard'])) $output['keyboard'] = sanitize_text_field($input['keyboard']);
    if (isset($input['disable_log'])) $output['disable_log'] = sanitize_text_field($input['disable_log']);
    $output['zapier'] = (isset($input['zapier']) && $input['zapier']) ? '1' : '';
    return $output;
}

add_action( 'init', function() {
    add_action( 'publish_page', 'telegram_send_post_notification', 10, 2 );
    foreach ( get_post_types( array( 'public' => true, '_builtin' => false ), 'names' ) as $cpt ) {
        if ( post_type_supports( $cpt, 'custom-fields' ) ) {
            add_action( 'future_'.$cpt, 'telegram_on_post_scheduled', 10, 2 );
            add_action( 'publish_'.$cpt, 'telegram_send_post_notification', 10, 2 );
            add_action( 'publish_future_'.$cpt, 'telegram_send_post_notification_future', 10, 2 );
        }
    }
});

require 'custom-post-types.php';

function telegram_defaults() {
    // Set API key if not set
    if (!get_option('wp_telegram_apikey')) {
        update_option('wp_telegram_apikey', md5(microtime() . rand() . get_site_url()));
    }
    // Set dispatches if not set
    if (!get_option('wp_telegram_dispatches')) {
        update_option('wp_telegram_dispatches', 0);
    }
    // Set main plugin options
    $defaults = array(
        'token'         => '',
        'zapier'        => '',
        'disable_log'   => '',
        'wmgroup'       => 'Welcome!',
        'wmuser'        => 'Welcome, %FIRST_NAME%!',
        'posttemplate'  => '%TITLE%' . PHP_EOL . PHP_EOL . '%LINK%',
        'bmuser'        => 'Bye, %FIRST_NAME%. Type /start to enable the bot again.',
        'keyboard'      => ''
    );
    $my_options = get_option('wp_telegram');
    if (!is_array($my_options)) {
        $my_options = array();
    }
    $changed = false;
    foreach ($defaults as $key => $value) {
        if (!isset($my_options[$key])) {
            $my_options[$key] = $value;
            $changed = true;
        }
    }
    if ($changed) {
        update_option('wp_telegram', $my_options);
    }
}

add_action('template_redirect', function(){
	global $wp_query;
	if ( isset( $_GET[get_option('wp_telegram_apikey')] ) && $wp_query->query[ get_option('wp_telegram_apikey') ]) {
        status_header( 200 );
		require 'parse.php';
	} else if ( isset( $_GET['zap'] ) && $wp_query->query['zap']) {
        if ($_GET['zap'] == get_option('wp_telegram_apikey') && telegram_option('zapier')) {
            status_header( 200 );
            $json = file_get_contents('php://input');
            if (!$json) {
                return;
            }
            $data = (array) json_decode($json, TRUE);
            telegram_log('------>', 'ZAPIER', json_encode((array) file_get_contents("php://input")));
            telegram_sendmessagetoall($data['hook']);
        }
	}
});

add_filter('query_vars', function($vars){
	$vars[] = get_option('wp_telegram_apikey');
    $vars[] = 'zap';
	return $vars;
});

function telegram_option($name)
{
	$options = get_option('wp_telegram');
	if (isset($options[$name])) {
		return $options[$name];
	}
	return false;
}

add_filter('user_can_richedit', function($default){
	global $post;
	if ('telegram_commands' == get_post_type($post)) return false;
	return $default;
});

function telegram_log($action, $chat_id, $text) {
    if (telegram_option('disable_log')) {
        return; // Do not log if the disable_log option is enabled
    }

    $actual_log = get_option('wp_telegram_log');

    if ( !is_array( $actual_log ) ) {
        $actual_log = array();
    }

    $new_log = array();
    $new_log[] = array( $action, date('m/d/Y H:i:s ', time()), sanitize_text_field($chat_id), sanitize_text_field($text) );
    
    $merge = array_merge( $new_log, $actual_log );

    update_option('wp_telegram_log', $merge );
}

function telegram_parsetext($text, $type, $chat_id) {
    remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );

    if ($type == 'text') {
        $text = str_replace('<b>', '*', $text);
        $text = str_replace('</b>', '*', $text);
    }

    $text = str_replace('%CHAT_ID%', $chat_id, $text);

    $o = get_page_by_title( $chat_id, OBJECT, 'telegram_subscribers');
    if ($o) {
        $text = str_replace('%FIRST_NAME%', get_post_meta($o->ID, 'telegram_first_name', true), $text);
        $text = str_replace('%LAST_NAME%', get_post_meta($o->ID, 'telegram_last_name', true), $text);
        $text = str_replace('%LOCATION_LATITUDE%', get_post_meta($o->ID, 'telegram_last_latitude', true), $text);
        $text = str_replace('%LOCATION_LONGITUDE%', get_post_meta($o->ID, 'telegram_last_longitude', true), $text);
    } else {
        $o = get_page_by_title( $chat_id, OBJECT, 'telegram_groups');
        if ($o) {
            $text = str_replace('%FIRST_NAME%', get_post_meta($o->ID, 'telegram_name', true), $text);
            $text = str_replace('%LAST_NAME%', get_post_meta($o->ID, '', true), $text);
        }
    }

    return str_replace('×', 'x', str_replace('_', '\_', strip_tags( html_entity_decode( apply_filters('the_content', $text ) ) )));
}

function telegram_get_reply_markup($id) {

    $value = apply_filters( 'telegram_get_reply_markup_filter', $id );
    if ( $value != $id ) {
        return $value;
    }

    $kt = get_post_meta( $id, 'telegram_kt', true );
    $ikt = get_post_meta( $id, 'telegram_ikt', true );

    if ( $id && $kt ) {
        if ( get_post_meta( $id, 'telegram_otk', true ) ) {
            $otk = true;
        } else {
            $otk = false;
        }
        return array(
            'keyboard' => telegram_get_keyboard_layout( $kt ),
            'resize_keyboard' => true,
            'one_time_keyboard' => $otk
        );
    } else if ( $id && $ikt ) {
        return array(
            'inline_keyboard' => telegram_get_inline_keyboard_layout( $ikt ),
        );
    } else if ( telegram_option('keyboard') ) {
        return array(
            'keyboard' => telegram_get_keyboard_layout( telegram_option('keyboard') ),
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        );
    } else if ( $id ) {
        return array(
            'hide_keyboard' => true,
        );
    }
}

function telegram_sendmessage( $chat_id, $text, $reply_markup = null, $disable_web_page_preview = false, $disable_notification = false ) {
	
    if ( !$text || !$chat_id ) { return; }

    if ( $reply_markup ) {
        $reply_markup = json_encode( $reply_markup, JSON_UNESCAPED_UNICODE );
    } else if (  is_int( $text ) && get_post_type( $text ) == 'telegram_commands' ) {
        $rm = telegram_get_reply_markup($text);
        if ($rm) {
            $reply_markup = json_encode( $rm, JSON_UNESCAPED_UNICODE );
        }
        $text = get_post_field('post_content', $text);
    } else if ( strpos( $chat_id, '@' ) === false ) {
        $rm = telegram_get_reply_markup( 0 );
        if ($rm) {
            $reply_markup = json_encode( $rm, JSON_UNESCAPED_UNICODE );
        }
    }

    $text = telegram_parsetext($text, 'text', $chat_id);

    if ( !$text ) { return; }

    $url = 'https://api.telegram.org/bot' . telegram_option('token') . '/sendMessage';
    $parse_mode = 'Markdown';

    if ( $reply_markup != null ) {
        $data = compact('chat_id', 'text', 'parse_mode', 'reply_markup', 'disable_web_page_preview', 'disable_notification');
    } else {
        $data = compact('chat_id', 'text', 'disable_web_page_preview', 'disable_notification');
    }

    $url_call = $url . '?' . http_build_query($data);
	@file_get_contents( $url_call );

    telegram_increase_dispatch();

    if ( strpos( $http_response_header['0'], '400 Bad Request' ) !== false ) {
        telegram_log('####', $chat_id, 'Error: incorrect parameters due to: '.$http_response_header['0'].' '.implode(' ', $data ));
        return false;
    } else if ( strpos( $http_response_header['0'], '403 Forbidden' ) !== false ) {
        telegram_log('####', $chat_id, 'User removed because bot has been blocked: '.$http_response_header['0']);
        wp_delete_post( telegram_getid( $chat_id ) );
        return false;
    }

    telegram_log('<<<< TXT', $chat_id, $text);
    return true;
}

function telegram_build_reply_markup( $keyboard_template, $one_time = false, $resize = true ) {
    return array(
        'keyboard' => telegram_get_keyboard_layout( $keyboard_template ),
        'resize_keyboard' => $resize,
        'one_time_keyboard' => $one_time
    );
}

function telegram_persian_convert_int($string) {
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $num = range(0, 9);
    return str_replace($persian, $num, $string);
}

function telegram_sendphoto($chat_id, $caption, $photo) {

    if (  is_int( $caption ) && get_post_type( $caption ) == 'telegram_commands' ) {
        $rm = telegram_get_reply_markup($caption);
        if ($rm) {
            $reply_markup = json_encode( $rm );
        }
        $caption = get_post_field('post_content', $caption);
    } else {
        $rm = telegram_get_reply_markup( 0 );
        if ($rm) {
            $reply_markup = json_encode( $rm );
        }
    }

    $caption = telegram_parsetext($caption, 'photo', $chat_id);
	$url = 'https://api.telegram.org/bot' . telegram_option('token') . '/sendPhoto';
	$photo = getFullPath($photo);
	$data = compact('chat_id', 'caption', 'photo', 'reply_markup');
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime_type = finfo_file($finfo, $data['photo']);
	$data['photo'] = new CurlFile($data['photo'], $mime_type, $data['photo']);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$response = json_decode(curl_exec($ch) , true);
	telegram_log('<<<< PHO', $chat_id, $caption);
    telegram_increase_dispatch();
	return;
}
function telegram_get_keyboard_layout($template) {
    return array_map ( function ($_) {return explode (',', $_);}, explode (';', $template) );
}

function telegram_get_inline_keyboard_layout($template) {
    return (array)json_decode( $template );
    //return [[ [ 'text' => 'Some button text 1', 'callback_data' => '1' ], [ 'text' => 'Some button text 2', 'callback_data' => '2' ] ]];
}

//Check if point is within a distance (max_distance required)
function telegram_location_haversine_check ( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $max_distance, $min_distance = 0, $earthRadius = 6371000) {
  $distance = telegram_location_haversine_distance ( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius);
  return ( $distance >= 0 && ($distance < $max_distance) );
}

//Calculate the distance
function telegram_location_haversine_distance ( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {

  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
  cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return (int)($angle * $earthRadius);
}

function telegram_increase_dispatch() {
    $d = get_option('wp_telegram_dispatches');
    update_option( 'wp_telegram_dispatches', ++$d);
}

function telegram_sendmessagetoall($message, $reply_markup = false, $disable_web_page_preview = false, $disable_notification = false ) {
    telegram_sendmessage_channel($message, $reply_markup, $disable_web_page_preview, $disable_notification );
    telegram_sendmessage_groups($message, $reply_markup, $disable_web_page_preview, $disable_notification );
    telegram_sendmessage_users($message, $reply_markup, $disable_web_page_preview, $disable_notification );
}

function telegram_sendmessage_users($message, $reply_markup = false, $disable_web_page_preview = false, $disable_notification = false ) {
    $args = array(
        'post_type' => 'telegram_subscribers',
        'post_per_page' => -1,
				'nopaging' => true
    );

    query_posts($args);
        while (have_posts()):
            the_post();
            telegram_sendmessage( get_post_field( 'post_title', get_the_id() ), $message, $reply_markup, $disable_web_page_preview, $disable_notification );
        endwhile;
}

function telegram_sendmessage_groups($message) {
    $args = array(
        'post_type' => 'telegram_groups',
        'post_per_page' => -1,
				'nopaging' => true
    );

     query_posts($args);

        $count = 0;
        while (have_posts()):
            the_post();
            telegram_sendmessage( get_post_field( 'post_title', get_the_id() ), $message);
            $count++;
        endwhile;
}

function telegram_sendmessage_channel( $message, $reply_markup = false, $disable_web_page_preview = false, $disable_notification = false ) {
    if ( telegram_option('channelusername') ) {
        telegram_sendmessage( telegram_option('channelusername'), $message, $reply_markup, $disable_web_page_preview, $disable_notification );
    }
}

function getFullPath($url) {
	return realpath(str_replace(get_bloginfo('url') , '.', $url));
}

function telegram_geturl() {
	return 'https://api.telegram.org/bot' . telegram_option('token') . '/';
}

function telegram_getapiurl() {
	return get_site_url() . '/?' . get_option('wp_telegram_apikey') . '=1';
}

add_filter('enter_title_here', function($input) {
	global $post_type;
	if (is_admin() && 'telegram_commands' == $post_type) return __('Type here your command', 'telegram-bot').' <small><small>(eg. <b>/contacts</b> or <b>help</b>)</small></small>';
	return $input;
} );

function telegram_getid($title) {
    $page = get_page_by_title( $title, OBJECT, 'telegram_subscribers' );
	if (!$page) {
		$page = get_page_by_title( $title, OBJECT, 'telegram_groups' );
	}
    if ( $page ) {
        return $page->ID;
    } else {
        return false;
    }
}

add_action( 'admin_print_footer_scripts', function() {
    global $post;
    if (wp_script_is('quicktags') && $post->post_type == 'telegram_commands' ){
?>
    <script type="text/javascript">
    QTags.addButton( 'wpt_bold', '*Bold*', '*', '*', 'b', 'Bold', 1 );
    QTags.addButton( 'wpt_italic', '_Italic_', '_', '_', 'i', 'Italic', 2 );
    QTags.addButton( 'wpt_link', '[text](url)', '[text](', ')', 'l', 'Link', 3 );
    </script>
<?php
    }
} );

add_filter( 'quicktags_settings', function($qtInit) {
    global $post;
    if ( $post && $post->post_type == 'telegram_commands' ) {
        $qtInit['buttons'] = ',';
    }
    return $qtInit;
}, 10, 1 );

function telegram_get_data_array() {
    $json = file_get_contents('php://input');

    if (!$json) {
        return false;
    }

    return (array)json_decode($json, TRUE);
}

function telegram_download_file( $telegram_user_id, $file_id, $directory = '' ) {
	$url =  telegram_geturl().'getFile?file_id='.$file_id;
	$response = file_get_contents($url);

	if (!$response) {
		return;
	}

	$data = (array)json_decode($response, TRUE);
	$plugin_post_id = telegram_getid( $telegram_user_id );
	$remote_url =  'https://api.telegram.org/file/bot'.telegram_option('token').'/'.$data['result']['file_path'];
	$local_dir = $local_url = ABSPATH.'wp-content/uploads/telegram-bot/'.$plugin_post_id.$directory;
	$ext = pathinfo($remote_url, PATHINFO_EXTENSION);
	$file_name = time().'.'.$ext;
	$local_url = $local_dir.'/'.$file_name;
	if ( !copy( $remote_url, $local_url) ) {
		mkdir( $local_dir, 0755, true);
		if ( !copy( $remote_url, $local_url) ) {
			telegram_log('', $telegram_user_id, 'Cannot write file image from '.$remove_url.' to '.$local_url);
			return false;
		} else {
			telegram_log('', $telegram_user_id, 'Directory created for incoming image');
			return false;
		}
	} else {
		telegram_log('', $telegram_user_id, 'Received and saved image');
		return get_site_url() . '/wp-content/uploads/telegram-bot/'.$plugin_post_id.'/'.$file_name;
	}
}

add_action('widgets_init', function() {
    require 'widget.php';
    register_widget('telegram_widget');
});

?>
