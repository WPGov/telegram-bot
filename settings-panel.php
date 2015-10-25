<div class="wrap">
   <h2>Settings</h2>
   <form method="post" action="options.php">
      <?php
         settings_fields('wp_telegram_options');
         ?>
      <?php
         telegram_defaults();
         $options = get_option('wp_telegram');
         if (isset($_GET['settings-updated'])) {
             $url = telegram_geturl() . 'setWebhook?url=';
             if (telegram_option('mode')) {
                 $url .= 'https://wptele.ga/?key=' . get_option('wp_telegram_apikey');
             } else {
                 $url .= telegram_getapiurl();
             }
             json_decode(file_get_contents($url), true);
             telegram_log('@@@@@@', 'webhook updated', $url);
         }
         ?>
      <table class="form-table">
         <tr valign="top">
            <th scope="row"><label for="apikey">Plugin Api Key</label></th>
            <td><input disabled id="apikey" type="text" name="wp_telegram_apikey" value="<?php
               echo get_option('wp_telegram_apikey');
               ?>" size="55" />
               <br><small>This is the unique key generated for your website. Keep it secret!</small>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row"><label for="token">Bot Token</label></th>
            <td><input id="token" type="text" name="wp_telegram[token]" value="<?php
               echo $options['token'];
               ?>" size="55" />
               <br><small>Your Telegram Bot authentication key. Get one from <a href="https://wptele.ga/?p=101" target="_blank">BotFather</a></small>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row"><label for="mode">Connection Mode</label></th>
            <td>
               <select name="wp_telegram[mode]">
                  <option <?php
                     if (!is_ssl()) {
                         echo 'disabled';
                     }
                     ?> value="0"<?php
                     if ($options['mode'] == 0) {
                         echo ' selected="selected"';
                     }
                     ?>>Telegram WebHooks</option>
                  <option value="1"<?php
                     if ($options['mode'] == 1) {
                         echo ' selected="selected"';
                     }
                     ?>>WPTele.ga Platform</option>
               </select>
               <br><small>
               <?php
                  if (is_ssl()) {
                      echo 'You have SSL, so you are allowed to choose between your server or WPTele.ga platform for sending messages';
                  } else {
                      echo 'You are not using SSL, so you can\'t send messages using Telegram webhook. The only way is to get a WPTele.ga subscription';
                  }
                  ?>
               <br>(to use WPTele.ga you need an active subscription. Get it <a href="https://wptele.ga" target="_blank">here</a>)
               </small>
            </td>
         </tr>
          <tr valign="top"><th scope="row"><label for="zapier">Enable Zapier</label></th>
					<td><input id="zapier" name="wp_telegram[zapier]" type="checkbox" value="1" <?php checked('1', $options['zapier']); ?> /><br>
<small>enable zapier integration &bull; Beta &bull; <a href="https://zapier.com/developer/invite/26805/1ec54299d4307c0b86b7417d0866ff25/">Click here to get an invite</a></small></td>
				</tr>
      </table>
      <p class="submit">
         <input type="submit" class="button-primary" value="<?php
            _e('Save Changes');
            ?>" />
      </p>
   </form>
</div>