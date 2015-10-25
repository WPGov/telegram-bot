<?php
/*
Plugin Name:  Telegram Bot
Plugin URI:  http://wptele.ga
Version:      0.10
Author:       Marco Milesi
Author URI:  http://marcomilesi.ml
Contributors: Milmor
Text Domain:  telegram-bot
Domain Path: /languages
*/

function telegram_subscribers_cpt() { require 'custom-post-types.php'; }

add_action('init', 'telegram_subscribers_cpt', 0);
add_action('admin_menu', 'register_my_custom_menu_page');

function register_my_custom_menu_page()
{
	add_menu_page('Telegram Dashboard', 'Telegram', 'manage_options', 'telegram_main', 'telegram_main_panel', plugins_url('telegram-bot/img/dashicon.png') , 25);
	add_submenu_page('telegram_main', 'Subscribers', 'Subscribers', 'manage_options', 'edit.php?post_type=telegram_subscribers');
	add_submenu_page('telegram_main', 'Groups', 'Groups', 'manage_options', 'edit-tags.php?taxonomy=telegram_groups');
    add_submenu_page('telegram_main', 'New Message', 'New Message', 'manage_options', 'telegram_send', 'telegram_send_panel');
	add_submenu_page('telegram_main', 'Commands', 'Commands', 'manage_options', 'edit.php?post_type=telegram_commands');
	add_submenu_page('telegram_main', 'Settings', 'Settings', 'manage_options', 'telegram_settings', 'telegram_settings_panel');
	add_submenu_page('telegram_main', 'Log', 'Log', 'manage_options', 'telegram_log', 'telegram_log_panel');
}

function telegram_main_panel()
{
	require 'main-panel.php';

}

function telegram_settings_panel()
{
	require 'settings-panel.php';

}

function telegram_send_panel()
{
	require 'send-panel.php';

}

function telegram_log_panel()
{
	if (isset($_GET['tbclear'])) {
		delete_option('wp_telegram_log');
		wp_redirect(esc_url(remove_query_arg('tbclear')));
		exit;
	}

	echo '<div class="wrap"><h2>Telegram Bot <b>History</b> <a href="admin.php?page=telegram_log&tbclear=1" class="add-new-h2">Clear Log</a></h2>
    <table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>
        <th style="width: 5%;" class="manage-column" scope="col">Type</th>
        <th style="width: 10%;" class="manage-column" scope="col">Date</th>
        <th style="width: 10%;" class="manage-column" scope="col">Author</th>
        <th id="columnname" class="manage-column" scope="col">Description</th>
    </tr>
    </thead>

    <tbody>' . get_option('wp_telegram_log') . '</tbody>
</table></div>';
}

function wp_telegram_start()
{
	register_setting('wp_telegram_options', 'wp_telegram');
	require 'columns.php';

}

add_action('admin_init', 'wp_telegram_start');

function telegram_defaults()
{
	require 'defaults.php';

}

function telegram_plugin_parse_request()
{
	global $wp_query;
	if ($wp_query->query[ get_option('wp_telegram_apikey') ]) {
		require 'parse.php';
	} else if ($wp_query->query['zap']) {
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

function telegram_parsetext($text, $type) {
    remove_filter( 'the_content', 'wpautop' );
    if ($type == 'text') {
        $text = str_replace('<b>', '*', $text);
        $text = str_replace('</b>', '*', $text);
    }
    //return $text; return do_shortcode( $text ); return html_entity_decode( apply_filters('the_content', $text ) );
    return strip_tags( html_entity_decode( apply_filters('the_content', $text ) ) );
}

function telegram_sendmessage($chat_id, $text)
{   
    $text = telegram_parsetext($text, 'text');	
	$url = 'https://api.telegram.org/bot' . telegram_option('token') . '/sendMessage';
    $parse_mode = 'Markdown';
	$data = compact('chat_id', 'text', 'parse_mode');
	json_decode(file_get_contents($url . '?' . http_build_query($data)) , true);
	telegram_log('<-TXT-', $chat_id, $text);
}

function telegram_sendmessagetoall($message) {
    query_posts('post_type=telegram_subscribers&posts_per_page=-1');

        $count = 0;
        while (have_posts()):
            the_post();
            if ( telegram_useractive( get_the_title() ) ) {
                telegram_sendmessage(get_the_title(), $message);
                $count++;
            }
        endwhile; 
}

function getFullPath($url)
{
	return realpath(str_replace(get_bloginfo('url') , '.', $url));
}

function telegram_sendphoto($chat_id, $caption, $photo)
{    		$caption = telegram_parsetext($caption, 'photo');
		$url = 'https://api.telegram.org/bot' . telegram_option('token') . '/sendPhoto';
	$photo = getFullPath($photo);
	$data = compact('chat_id', 'caption', 'photo');
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime_type = finfo_file($finfo, $data['photo']);
	$data['photo'] = new CurlFile($data['photo'], $mime_type, $data['photo']);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$response = json_decode(curl_exec($ch) , true);
	telegram_log('<-PHO-', $chat_id, $caption);
	return;
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
	if (is_admin() && 'telegram_commands' == $post_type) return 'Type here your command <small><small>(eg. <b>/contacts</b> or <b>help</b>)</small></small>';
	return $input;
}

function telegram_useractive($userid) {
    if ( get_post_meta( telegram_getid($userid), 'telegram_status' ) ) {
        return false;
    } else {
        return true;
    }
}

function telegram_getid($title) {
    $page = get_page_by_title( $title, OBJECT, 'telegram_subscribers' );
    return $page->ID;
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

?>