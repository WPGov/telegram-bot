<?php
  if (!get_option('wp_telegram_apikey')) {
      update_option('wp_telegram_apikey', md5(microtime() . rand() . get_site_url()));
  }

  if (!get_option('wp_telegram_dispatches')) {
      update_option('wp_telegram_dispatches', 0);
  }

  $defaults   = array(
      array('token', ''),
      array('mode', ''),
      array('zapier', ''),
      array('wmgroup', 'Welcome!'),
      array('wmuser', 'Welcome, %FIRST_NAME%!'),
      array('posttemplate', '%TITLE%'.PHP_EOL.PHP_EOL.'%LINK%'),
      array('bmuser', 'Bye, %FIRST_NAME%. Type /start to enable the bot again.'),
      array('keyboard', '')
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
