<div class="wrap">
  <h2>Settings
  </h2>
  <form method="post" action="options.php">
    <?php
settings_fields( 'wp_telegram_options');
$options=get_option( 'wp_telegram');
if (isset($_GET[ 'settings-updated'])) {
$url=telegram_geturl() . 'setWebhook?url=';
if (telegram_option( 'mode')) {
$url .='https://wptele.ga/?key=' . get_option( 'wp_telegram_apikey');
} else {
$url .=telegram_getapiurl();
}
json_decode(file_get_contents($url), true);
telegram_log( '####', __('"WebHook" updated', 'telegram-bot'), $url);
}
?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="apikey">Plugin Api Key
          </label>
        </th>
        <td>
          <input disabled id="apikey" type="text" name="wp_telegram_apikey" value="<?php echo get_option('wp_telegram_apikey'); ?>" size="55" />
          <br>
          <small>This is the unique key generated for your website.
            <strong>Keep it secret!
            </strong>
          </small>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="token">Bot Token
          </label>
        </th>
        <td>
          <input id="token" type="text" name="wp_telegram[token]" value="<?php echo $options['token']; ?>" size="55" />
          <br>
          <small>Your Telegram Bot authentication key. Get one from
            <a href="https://wptele.ga/?p=101" target="_blank">BotFather
            </a>.
            <strong>Keep it secret!
            </strong>
          </small>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row">
          <label for="mode">
            <?php _e('Connection Mode', 'telegram-bot'); ?>
          </label>
        </th>
        <td>
          <select name="wp_telegram[mode]">
            <option
                    <?php if (!is_ssl()) { echo 'disabled'; } ?> value="0"
            <?php if ($options[ 'mode']==0 ) { echo ' selected="selected"'; } ?>>Telegram WebHooks
            </option>
          <option value="1"
                  <?php if ($options[ 'mode']==1 ) { echo ' selected="selected"'; } ?>>WPTele.ga Platform
      </option>
      </select>
    <br>
    <small>
      <?php
if (is_ssl()) {
echo 'You have SSL, so you are allowed to choose between your server or WPTele.ga platform for sending messages';
} else {
echo 'You are not using SSL, so you can\'t send messages using Telegram webhook. The only way is to get a WPTele.ga subscription';
}
?>
      <br>(to use WPTele.ga you need an active subscription. Get it
      <a href="https://wptele.ga" target="_blank">here
      </a>)
    </small>
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
           <?php checked( '1', $options[ 'zapier']); ?> />
    <br>
    <small>enable zapier integration &bull; Beta &bull;
      <a href="https://zapier.com/developer/invite/26805/1ec54299d4307c0b86b7417d0866ff25/">Click here to get an invite
      </a>
    </small>
  </td>
</tr>
<tr valign="top">
  <th scope="row">
    <label for="channelusername">
      <?php _e('Channel Username', 'telegram-bot'); ?>
    </label>
  </th>
  <td>
    <input id="channelusername" type="text" name="wp_telegram[channelusername]" value="<?php echo $options['channelusername']; ?>" size="55" />
    <br>
    <small>Insert here your channel username if you want to stream messages to it. Ex.
      <b>@sanpellegrinoterme
      </b>
      <br>The bot must be administrator in your channel
    </small>
  </td>
</tr>
<tr valign="top">
  <th scope="row">
    <label for="wmuser">
      <?php _e('Start Message', 'telegram-bot'); ?>
      <br>
      <small>
        <?php _e('for Private Conversations', 'telegram-bot'); ?>
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
        <?php _e('for Private Conversations', 'telegram-bot'); ?>
      </small>
    </label>
  </th>
  <td>
    <input id="emuser" type="text" name="wp_telegram[emuser]" value="<?php echo $options['emuser']; ?>" size="55" />
    <br>
    <small>This will be shown when the command doesn't exist.
    </small>
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
      <small>for Private Conversations
      </small>
    </label>
  </th>
  <td>
    <input id="keyboard" type="text" name="wp_telegram[keyboard]" value="<?php echo $options['keyboard']; ?>" size="55" />
    <br>
    <small>Example:
      <b>1,2,3;4,5,6;Text
      </b>
    </small>
  </td>
</td>
</tr>
<tr valign="top">
    <th scope="row">
        <label for="posttemplate"><?php _e('Post Template', 'telegram-bot'); ?>
            <br><small><?php _e('for posts broadcast', 'telegram-bot'); ?></small> </label>
    </th>
    <td>
    <textarea id="posttemplate" rows="4" columns="55" class="widefat" name="wp_telegram[posttemplate]"><?php echo $options['posttemplate']; ?></textarea>
    <br><small>Allowed placeholders: <b>%TITLE% %LINK% %EXCERPT%</b></small> </td></td>
</tr>
</table>
<p class="submit">
  <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
</p>
</form>
</div>
