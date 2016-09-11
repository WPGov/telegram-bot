<?php
/*
Plugin Name:  Telegram Bot & Channel
Plugin URI:  http://wptele.ga
Description: Stream your content to Telegram. Create your bot, Manage your responders and Send your news directly from your WordPress website! Zapier compatible
Version:      1.7
Author:       Marco Milesi
Author URI:  http://marcomilesi.ml
Contributors: Milmor
Text Domain:  telegram-bot
Domain Path: /languages
*/

add_action( 'plugins_loaded', 'telegram_load_textdomain' );
function telegram_load_textdomain() { load_plugin_textdomain( 'telegram-bot' ); }

require 'custom-post-types.php';

add_action('admin_menu', 'register_my_custom_menu_page');

function register_my_custom_menu_page()
{
	add_menu_page('Telegram Dashboard', 'Telegram', 'manage_options', 'telegram_main', 'telegram_main_panel', 'dashicons-megaphone' , 25);
	add_submenu_page('telegram_main', __('Subscribers', 'telegram-bot'), __('Subscribers', 'telegram-bot'), 'manage_options', 'edit.php?post_type=telegram_subscribers');
	add_submenu_page('telegram_main', __('Groups', 'telegram-bot'), __('Groups', 'telegram-bot'), 'manage_options', 'edit.php?post_type=telegram_groups');
    add_submenu_page('telegram_main', __('New message', 'New message'), __('New message', 'telegram-bot'), 'manage_options', 'telegram_send', 'telegram_send_panel');
	add_submenu_page('telegram_main', __('Commands', 'telegram-bot'), __('Commands', 'telegram-bot'), 'manage_options', 'edit.php?post_type=telegram_commands');
	add_submenu_page('telegram_main', __('Settings', 'telegram-bot'), __('Settings', 'telegram-bot'), 'manage_options', 'telegram_settings', 'telegram_settings_panel');
	add_submenu_page('telegram_main', 'Log', 'Log', 'manage_options', 'telegram_log', 'telegram_log_panel');
}

function telegram_main_panel() { require 'main-panel.php'; }

function telegram_settings_panel() { require 'settings-panel.php'; }

function telegram_send_panel() { require 'send-panel.php'; }

function telegram_log_panel() {
	if (isset($_GET['tbclear'])) {
		delete_option('wp_telegram_log');
		wp_redirect(esc_url(remove_query_arg('tbclear')));
		exit;
	}

	echo '<div class="wrap"><h2>Telegram Bot <b>Log</b> <a href="admin.php?page=telegram_log&tbclear=1" class="add-new-h2">'.__('Clear Log', 'telegram-bot').'</a></h2>
    <table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>
        <th style="width: 5%;" class="manage-column" scope="col">'.__('Type', 'telegram-bot').'</th>
        <th style="width: 10%;" class="manage-column" scope="col">'.__('Date', 'telegram-bot').'</th>
        <th style="width: 10%;" class="manage-column" scope="col">'.__('Author', 'telegram-bot').'</th>
        <th id="columnname" class="manage-column" scope="col">'.__('Description', 'telegram-bot').'</th>
    </tr>
    </thead>

    <tbody>' . get_option('wp_telegram_log') . '</tbody>
</table></div>';
}

function wp_telegram_start()
{
	register_setting('wp_telegram_options', 'wp_telegram');

	require 'columns.php';
    require 'admin-messages.php';

    $arraytbpv = get_plugin_data ( __FILE__ );
    $nuova_versione = $arraytbpv['Version'];

    if ( version_compare( get_option('wp_telegram_version'), $nuova_versione, '<')) {
        telegram_defaults();
        update_option( 'wp_telegram_version', $nuova_versione );
    }

}

add_action('admin_init', 'wp_telegram_start');

function telegram_defaults() { require 'defaults.php'; }

function telegram_plugin_parse_request()
{
	global $wp_query;
	if ( isset( $_GET[get_option('wp_telegram_apikey')] ) && $wp_query->query[ get_option('wp_telegram_apikey') ]) {
		require 'parse.php';
	} else if ( isset( $_GET['zap'] ) && $wp_query->query['zap']) {
        if ($_GET['zap'] == get_option('wp_telegram_apikey') && telegram_option('zapier')) {
            require 'zapier.php';
        }
	}
}

add_action('template_redirect', 'telegram_plugin_parse_request');
add_filter('query_vars', 'telegram_query_vars');

function telegram_query_vars($vars)
{
	$vars[] = get_option('wp_telegram_apikey');
    $vars[] = 'zap';
	return $vars;
}

function telegram_option($name)
{
	$options = get_option('wp_telegram');
	if (isset($options[$name])) {
		return $options[$name];
	}

	return false;
}

add_filter('user_can_richedit', 'disable_for_cpt');

function disable_for_cpt($default)
{
	global $post;
	if ('telegram_commands' == get_post_type($post)) return false;
	return $default;
}

function telegram_log($action, $chat_id, $text)
{
	update_option('wp_telegram_log', '<tr>
            <td>' . $action . '</td>
            <td>' . date('m/d/Y H:i:s ', time()) . '</td>
            <td>' . sanitize_text_field($chat_id) . '</td>
            <td>' . sanitize_text_field($text) . '</td>
        </tr>' . get_option('wp_telegram_log'));
}

function telegram_parsetext($text, $type, $chat_id) {
    remove_filter( 'the_content', 'wpautop' );
		remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );

    if ($type == 'text') {
        $text = str_replace('<b>', '*', $text);
        $text = str_replace('</b>', '*', $text);
    }

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
    //return $text; return do_shortcode( $text ); return html_entity_decode( apply_filters('the_content', $text ) );
		//return strip_tags( html_entity_decode( do_shortcode( $text ) ) );
    return strip_tags( html_entity_decode( apply_filters('the_content', $text ) ) );
}

function telegram_get_reply_markup($id) {

    $value = apply_filters( 'telegram_get_reply_markup_filter', $id );
    if ( $value != $id ) {
        return $value;
    }

    $kt = get_post_meta( $id, 'telegram_kt', true );
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

function telegram_sendmessage($chat_id, $text) {
    if ( !$text || !$chat_id ) { return; }

    if (  is_int( $text ) && get_post_type( $text ) == 'telegram_commands' ) {
        $reply_markup = json_encode( telegram_get_reply_markup($text) );
        $text = get_post_field('post_content', $text);
    } else {
        $reply_markup = json_encode( telegram_get_reply_markup( 0 ) );
    }
    $text = telegram_parsetext($text, 'text', $chat_id);

    if ( !$text ) { return; }

    $url = 'https://api.telegram.org/bot' . telegram_option('token') . '/sendMessage';
    $parse_mode = 'Markdown';

    if ( $reply_markup != 'null' ) {
        $data = compact('chat_id', 'text', 'parse_mode', 'reply_markup');
    } else {
        $data = compact('chat_id', 'text', 'parse_mode');
    }

	file_get_contents($url . '?' . http_build_query($data));

    telegram_increase_dispatch();

    if ( $http_response_header['0'] == 'HTTP/1.1 400 Bad Request') {
        telegram_log('####', $chat_id, 'Error: incorrect parameters due to: '.$http_response_header['0'].' '.implode(' ', $data ));
        return false;
    } else if ( $http_response_header['0'] == 'HTTP/1.1 403 Forbidden') {
        telegram_log('####', $chat_id, 'User removed because bot has been blocked: '.$http_response_header['0']);
        wp_delete_post( telegram_getid( $chat_id ) );
        return false;
    }

    telegram_log('<<<< TXT', $chat_id, $text);
    return true;
}

function telegram_persian_convert_int($string) {
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $num = range(0, 9);
    return str_replace($persian, $num, $string);
}

function telegram_sendphoto($chat_id, $caption, $photo) {

    if (  is_int( $caption ) && get_post_type( $caption ) == 'telegram_commands' ) {
        $reply_markup = json_encode( telegram_get_reply_markup($caption) );
        $caption = get_post_field('post_content', $caption);
    } else {
        $reply_markup = json_encode( telegram_get_reply_markup( 0 ) );
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

function telegram_sendmessagetoall($message) {

    if ( telegram_option('channelusername') ) {
        telegram_sendmessage( telegram_option('channelusername'), $message);
    }

    $args = array(
        'post_type' => 'telegram_groups',
        'post_per_page' => -1,
				'nopaging' => true
    );

     query_posts($args);

        $count = 0;
        while (have_posts()):
            the_post();
            telegram_sendmessage( get_post_field( 'post_name', get_the_id() ), $message);
            $count++;
        endwhile;


    $args = array(
        'post_type' => 'telegram_subscribers',
        'post_per_page' => -1,
				'nopaging' => true
    );

    query_posts($args);

        $count = 0;
        while (have_posts()):
            the_post();
            telegram_sendmessage( get_post_field( 'post_name', get_the_id() ), $message);
            $count++;
        endwhile;
}

function getFullPath($url)
{
	return realpath(str_replace(get_bloginfo('url') , '.', $url));
}

function telegram_geturl()
{
	return 'https://api.telegram.org/bot' . telegram_option('token') . '/';
}

function telegram_getapiurl()
{
	return get_site_url() . '/?' . get_option('wp_telegram_apikey') . '=1';
}

add_filter('enter_title_here', 'telegram_enter_title');

function telegram_enter_title($input)
{
	global $post_type;
	if (is_admin() && 'telegram_commands' == $post_type) return __('Type here your command', 'telegram-bot').' <small><small>(eg. <b>/contacts</b> or <b>help</b>)</small></small>';
	return $input;
}

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

function wpt_quicktagsadd() {
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
}
add_action( 'admin_print_footer_scripts', 'wpt_quicktagsadd' );

add_filter( 'quicktags_settings', 'wpt_quicktags', 10, 1 );
function wpt_quicktags( $qtInit ) {
    global $post;
    if ( $post && $post->post_type == 'telegram_commands' ) {
        $qtInit['buttons'] = ',';
    }
    return $qtInit;
}

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

?>
