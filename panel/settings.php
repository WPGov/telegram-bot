<?php function telegram_settings_page() { ?>

<div class="wrap">
  <h2>Settings</h2>

<?php if ( $_SERVER["SERVER_ADDR"] == '127.0.0.1' ) {
  echo '<div class="notice notice-warning"><p>'.__('Warning: the plugin <b>does not</b> work in localhost environments!', 'telegram-bot').'</p></div>';
} ?>

<form method="post" action="options.php">
  <?php
    settings_fields( 'wp_telegram_options');
    $options=get_option( 'wp_telegram');
    foreach( $options as $k => $v ) {
      $options[$k] = esc_attr( $v );
    }

    if ( isset($_GET['settings-updated']) ) {

        $url=telegram_geturl() . 'setWebhook?url=' . telegram_getapiurl();
        
        json_decode(file_get_contents($url), true);
        telegram_log( 'sys', 'Webhook update', $url);
    }
  ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="apikey">Plugin Api Key
          </label>
        </th>
        <td>
          <input readonly="readonly" id="apikey" type="text" name="wp_telegram_apikey" value="<?php echo get_option('wp_telegram_apikey'); ?>" size="55" />
          <br>
          <small>The unique key for your website: <b>Keep it secret!</b></small>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="token">Bot Token<br><span style="background:darkred;font-size:0.8em;padding:2px;color:white;"><?php _e('Required', 'telegram-bot'); ?></span></label>
        </th>
        <td>
          <input id="token" type="text" name="wp_telegram[token]" value="<?php echo $options['token']; ?>" size="55" />
          <br>
          <small><?php _e( 'Paste here your bot token. You can obtain it from Telegram <b>BotFather</b>', 'telegram-bot'); ?></small>
        </td>
      </tr
      <tr valign="top">
        <th scope="row">
          <label for="username">Bot Username<br><span style="background:darkred;font-size:0.8em;padding:2px;color:white;"><?php _e('Required', 'telegram-bot'); ?></span></label>
        </th>
        <td>
          <input id="username" type="text" name="wp_telegram[username]" value="<?php echo isset( $options['username'] ) ? $options['username'] : ''; ?>" size="55" />
          <br>
          <small>Telegram Bot username. Example: <b>mywebsite_bot</b></small>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="channelusername">
            <?php _e('Channel Username', 'telegram-bot'); ?>
          </label>
        </th>
        <td>
          <input id="channelusername" type="text" name="wp_telegram[channelusername]" value="<?php echo isset( $options['channelusername'] ) ? $options['channelusername'] : ''; ?>" size="55" />
          <br>
          <small>Insert your channel username (if you want to broadcast). Example: <b>@mywebsite</b>
            <br>The bot must be admin in your channel
          </small>
        </td>
      </tr>
  <tr valign="top">
    <th scope="row">
      <label>
        Webhook
      </label>
    </th>
    <td>
        <a class="page-title-action" onclick="alert('<?php echo telegram_getapiurl(); ?>');">Show private endpoint</a>
        <p style="font-size:0.9em;">
          This is your private Telegram webhook. Please keep it private and make sure it bypasses cache and firewall.
        </p>
    </td>
  </tr>
<tr valign="top">
  <th scope="row">
    <label for="zapier">
      <?php _e('Enable Zapier', 'telegram-bot'); ?>
    </label>
  </th>
  <td>
    <input id="zapier" name="wp_telegram[zapier]" type="checkbox" value="1"
          <?php checked( '1', ( isset( $options[ 'zapier'] ) && $options[ 'zapier'] ) ? 1 : 0 ); ?> />
    <br>
    <small>Zapier allows you to send messages from your own platforms
      <a href="https://zapier.com/developer/public-invite/88225/1d9f5d9ec76cde2aac6e356298aee8e8/">Click here to get an invite</a>
    </small>
  </td>
</tr>
<tr valign="top">
      <td colspan="2">
        <h3><?php _e('Posts broadcast', 'telegram-bot'); ?></h3>
        <p><?php _e('You can send post or custom post to Telegram when you publish or edit it.', 'telegram-bot'); ?></p>
        <p><?php _e('If you want to broadcast to a channel, please add the bot as admin in your channel.', 'telegram-bot'); ?></p>        
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="mode">
          <?php _e('Default content broadcast', 'telegram-bot'); ?>
        </label>
      </th>
      <td>
        <select name="wp_telegram[target]">
          <option><?php _e('Users, Groups, Channel', 'telegram-bot'); ?></option>
          <option value="1"<?php if (isset($options['target'])&&$options['target']==1 ) { echo ' selected="selected"'; } ?>><?php _e('Users', 'telegram-bot'); ?></option>
          <option value="2"<?php if (isset($options['target'])&&$options['target']==2 ) { echo ' selected="selected"'; } ?>><?php _e('Groups', 'telegram-bot'); ?></option>
          <option value="3"<?php if (isset($options['target'])&&$options['target']==3 ) { echo ' selected="selected"'; } ?>><?php _e('Users, Groups', 'telegram-bot'); ?></option>
          <option value="4"<?php if (isset($options['target'])&&$options['target']==4 ) { echo ' selected="selected"'; } ?>><?php _e('Channel', 'telegram-bot'); ?></option>
          <option value="5"<?php if (isset($options['target'])&&$options['target']==5 ) { echo ' selected="selected"'; } ?>><?php _e('All', 'telegram-bot'); ?></option>
        </select>
      <br>
      <small>
        <?php _e('Define the post broadcast targeting when you send content (for example posts or manual text).', 'telegram-bot'); ?>
        <br>
        <?php _e('You can also change this behaviour manually.', 'telegram-bot'); ?>
      </small>
      </td>
    </tr>
    <tr valign="top">
        <th scope="row">
            <label for="posttemplate"><?php _e('Post Template', 'telegram-bot'); ?></label>
        </th>
        <td>
        <textarea id="posttemplate" rows="4" class="widefat" name="wp_telegram[posttemplate]"><?php echo $options['posttemplate']; ?></textarea>
        <br><small><?php _e('Allowed placeholders: <b>%TITLE% %LINK% %EXCERPT% %CHAT_ID%</b>', 'telegram-bot'); ?></small> </td></td>
    </tr>
    <tr valign="top">
      <td colspan="2">
        <h3><?php _e('Interactive bot', 'telegram-bot'); ?></h3>
        <p>Only use this section if you want to build an interactive bot</p>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="wmuser">
          <?php _e('Start Message', 'telegram-bot'); ?>
          <br>
          <small>
            <?php _e('for users', 'telegram-bot'); ?>
          </small>
        </label>
      </th>
      <td>
        <input id="wmuser" type="text" name="wp_telegram[wmuser]" value="<?php echo $options['wmuser']; ?>" size="55" />
        <br>
        <small>
          <?php _e('Cannot be blank', 'telegram-bot'); ?>.
        </small>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="bmuser">
          <?php _e('Bye Message', 'telegram-bot'); ?>
          <br>
          <small>
            <?php _e('for Private Conversations', 'telegram-bot'); ?>
          </small>
        </label>
      </th>
      <td>
        <input id="bmuser" type="text" name="wp_telegram[bmuser]" value="<?php echo $options['bmuser']; ?>" size="55" />
        <br>
        <small>
          <?php _e('Cannot be blank', 'telegram-bot'); ?>.
        </small>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="emuser">
          <?php _e('Error Message', 'telegram-bot'); ?>
          <br>
          <small>
            <?php _e('for users', 'telegram-bot'); ?>
          </small>
        </label>
      </th>
      <td>
        <input id="emuser" type="text" name="wp_telegram[emuser]" value="<?php echo isset( $options['emuser'] ) ? $options['emuser'] : ''; ?>" size="55" />
        <br>
        <small><?php _e('This will be shown when the command doesn\'t exist.', 'telegram-bot'); ?></small>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="wmgroup">
          <?php _e('Start Message', 'telegram-bot'); ?>
          <br>
          <small>for Groups
          </small>
        </label>
      </th>
      <td>
        <input id="wmgroup" type="text" name="wp_telegram[wmgroup]" value="<?php echo $options['wmgroup']; ?>" size="55" />
        <br>
        <small>
          <?php _e('Cannot be blank', 'telegram-bot'); ?>.
        </small>
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">
        <label for="keyboard">
          <?php _e('Keyboard Template', 'telegram-bot'); ?>
          <br>
          <small><?php _e('for users', 'telegram-bot'); ?></small>
        </label>
      </th>
      <td>
        <input id="keyboard" type="text" name="wp_telegram[keyboard]" value="<?php echo $options['keyboard']; ?>" size="55" />
        <br>
        <small><?php _e('Example: <b>1,2,3;4,5,6;Text</b>', 'telegram-bot'); ?></small>
      </td>
    </tr>
</table>

<p class="submit">
  <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
</p>
</form>
</div>
<?php } ?>