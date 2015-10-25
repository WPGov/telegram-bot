<?php
if (!get_option('wp_telegram_apikey')) {
    update_option('wp_telegram_apikey', md5(microtime() . rand() . get_site_url()));
}
$defaults   = array(
    array('token', '0'),
    array('mode', '0'),
    array('zapier', '0')
);
$my_options = get_option('wp_telegram');
$conta = count($defaults);
for ($i = 0; $i < $conta; $i++) {
    if (!$my_options[$defaults[$i][0]]) {
        $my_options[$defaults[$i][0]] = $defaults[$i][1];
        update_option('wp_telegram', $my_options);
    }
}
?>