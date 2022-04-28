<div class="wrap">
  <h2>Settings</h2>

<div id="welcome-panel" class="welcome-panel">
	<div class="welcome-panel-content">
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<a class="button button-primary button-hero load-customize" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=F2JK36SCXKTE2">Make a <big>donation</big></a>
        <br>and follow us on <a href="https://www.facebook.com/WPGov">Facebook</a> and <a href="https://t.me/BotPressOrg">Telegram</a>
			</div>
			<div class="welcome-panel-column">
				<h3>Support</h3>
				<ul>
					<li><a href="https://www.botpress.org/docs/" class="welcome-icon welcome-write-blog">Need help?</a></li>
					<li><a href="https://www.botpress.org/support/" class="welcome-icon dashicons-sos">Forum</a></li>
				</ul>
			</div>
			<div class="welcome-panel-column welcome-panel-last">
				<h3>Credits</h3>
				<ul>
					<li><div class="welcome-icon dashicons-admin-users">Developed by <a href="https://www.marcomilesi.com/">Marco Milesi</a> - Telegram <a href="https://t.me/Milmor">@Milmor</a></div></li>
					<li>Need custom work? Feel free to <a href="https://www.marcomilesi.com/contact/">contact me</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="about__section is-feature has-accent-background-color">
  <p>
    This plugin is capable of broadcasting to channel and create interactive bots.
    <br>In both cases, you need to create a bot with BotFather and obtain the token.
    <br>If you want to broadcast to a channel, please add the bot as admin in your channel.
  </p>
</div>
<?php if ( $_SERVER["SERVER_ADDR"] == '127.0.0.1' ) {
  echo '<div class="notice notice-warning"><p>'.__('Warning: the plugin <b>does not</b> work in localhost environments!', 'telegram-bot').'</p></div>';
} ?>

<form method="post" action="options.php">
  <?php
    settings_fields( 'wp_telegram_options');
    $options=get_option( 'wp_telegram');

    if ( isset($_GET['settings-updated']) ) {

        $url=telegram_geturl() . 'setWebhook?url=';
        
        switch( telegram_option('mode') ) {
          case 0: // SSL
            $url .=telegram_getapiurl();
            break;
          case 2:
            $current_user = wp_get_current_user();
            json_decode( @file_get_contents( 'https://botpress.org/endpoint-tr.php?token='.get_option( 'wp_telegram_apikey').'&url='.get_site_url().'&email='.$current_user->user_email.'&fn='.$current_user->first_name.'&ln='.$current_user->last_name ), true );
            telegram_log( '#BOTPRESS#', __('Subscription updated', 'telegram-bot'), 'success');
            $url .='https://botpress.org/endpoint-td.php?key=' . get_option( 'wp_telegram_apikey');
            break;
        }
        json_decode(file_get_contents($url), true);
        telegram_log( '####', __('"WebHook" updated', 'telegram-bot'), $url);
    }
  ?>


<div class="about__section has-2-columns">
  <div class="column">
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
            <label for="token">Bot Token
            </label>
          </th>
          <td>
            <input id="token" type="text" name="wp_telegram[token]" value="<?php echo $options['token']; ?>" size="55" />
            <br>
            <small>Telegram Bot authentication key. <b>Keep it secret!</b>
            </small>
          </td>
        </tr
        <tr valign="top">
          <th scope="row">
            <label for="username">Bot Username
            </label>
          </th>
          <td>
            <input id="username" type="text" name="wp_telegram[username]" value="<?php echo $options['username']; ?>" size="55" />
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
            <input id="channelusername" type="text" name="wp_telegram[channelusername]" value="<?php echo $options['channelusername']; ?>" size="55" />
            <br>
            <small>Insert your channel username (if you want to broadcast). Example: <b>@mywebsite</b>
              <br>The bot must be admin in your channel
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
              <?php if ( isset( $options[ 'mode' ] ) && $options[ 'mode' ] == 0 ) { echo ' selected="selected"'; } ?>>Telegram WebHooks
              </option>
              <option value="2" <?php if ( isset( $options['mode'] ) && $options['mode'] == 2 ) { echo ' selected="selected"'; } ?>>BotPress.org</option>
        </select>
      <br>
      <small>
      <?php
        if ( is_ssl() ) {
          _e('SSL ACTIVE! Choose <b>Telegram webhooks</b> to get the best user experience!', 'telegram-bot');
        } else {
          _e('SSL NOT ACTIVE: choose BotPress.org to bypass webhook restriction', 'telegram-bot'); 
          echo '<br>';
          _e('<b>Warning</b>: by choosing BotPress.org some data, including your <b>website url</b> and <b>email</b>, will be sent to our servers.', 'telegram-bot');
          echo '<br>';
          _e('<b>BotPress.org</b> usage is subject to our ', 'telegram-bot');
          echo '<a href="https://www.botpress.org/privacy-policy/" target="_blank">';
          _e('Privacy Policy');
          echo '</a> and <a href="https://www.botpress.org/terms-conditions/" target="_blank">';
          _e('Terms & Conditions');
          echo '</a>. By choosing the service you automatically accept these conditions.';
        }
      ?>
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
            <?php checked( '1', ( isset( $options[ 'zapier'] ) && $options[ 'zapier'] ) ? 1 : 0 ); ?> />
      <br>
      <small>enable zapier integration &bull; Beta &bull;
        <a href="https://zapier.com/developer/invite/26805/1ec54299d4307c0b86b7417d0866ff25/">Click here to get an invite
        </a>
      </small>
    </td>
  </tr>
  </table>
</div>



  <div class="column">
    <table class="form-table">
      <tr valign="top">
        <td colspan="2">
          <h3><?php _e('Posts broadcast', 'telegram-bot'); ?></h3>
          <p><?php _e('You can send post or custom post to Telegram when you publish or edit it.', 'telegram-bot'); ?></p>
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
            <option value="1"<?php if ($options['target']==1 ) { echo ' selected="selected"'; } ?>><?php _e('Users', 'telegram-bot'); ?></option>
            <option value="2"<?php if ($options['target']==2 ) { echo ' selected="selected"'; } ?>><?php _e('Groups', 'telegram-bot'); ?></option>
            <option value="3"<?php if ($options['target']==3 ) { echo ' selected="selected"'; } ?>><?php _e('Users, Groups', 'telegram-bot'); ?></option>
            <option value="4"<?php if ($options['target']==4 ) { echo ' selected="selected"'; } ?>><?php _e('Channel', 'telegram-bot'); ?></option>
            <option value="5"<?php if ($options['target']==5 ) { echo ' selected="selected"'; } ?>><?php _e('All', 'telegram-bot'); ?></option>
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
          <input id="emuser" type="text" name="wp_telegram[emuser]" value="<?php echo $options['emuser']; ?>" size="55" />
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
  </div>
</div>

<div class="clear"></div>
<hr>
<p class="submit">
  <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
</p>
</form>
</div>
