<?php
// Security: Only allow admins to view this page
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

$options = (array) get_option('wp_telegram', array());
// Ensure all option values are safe for attribute output
foreach ($options as $k => $v) {
    $options[$k] = esc_attr($v);
}

// Handle webhook update after settings are saved
if (isset($_GET['settings-updated'])) {
    // Use WP HTTP API instead of file_get_contents for better error handling and security
    $url = telegram_geturl() . 'setWebhook?url=' . telegram_getapiurl();
    if (function_exists('wp_remote_get')) {
        $resp = wp_remote_get($url, array('timeout' => 5));
        $body = is_wp_error($resp) ? '' : wp_remote_retrieve_body($resp);
        json_decode($body, true);
    } else {
        // Fallback (rare): suppress errors but still attempt
        @json_decode(@file_get_contents($url), true);
    }
    telegram_log('sys', 'Webhook update', $url);
}
?>
<div class="wrap telegram-settings" style="max-width:900px;margin:auto;">
    <h1 style="margin-bottom:24px;">
        <?php _e('Telegram Bot Settings', 'telegram-bot'); ?>
        <a href="https://github.com/WPGov/telegram-bot" target="_blank" style="font-size:0.6em;margin-left:12px;vertical-align:middle;">View on GitHub</a>
    </h1>
    <p style="font-size:1.1em;">
        <?php _e('Configure your Telegram bot to broadcast content, interact with users, and manage settings.', 'telegram-bot'); ?><br>
        <?php _e('Follow the instructions carefully to ensure proper functionality.', 'telegram-bot'); ?><br>
        <?php _e('Need help? Visit our documentation or GitHub page.', 'telegram-bot'); ?>
    </p>
    <?php if ($_SERVER["SERVER_ADDR"] == '127.0.0.1') {
        echo '<div class="notice notice-warning"><p>' . __('Warning: the plugin <b>does not</b> work in localhost environments!', 'telegram-bot') . '</p></div>';
    } ?>
    <?php if (isset($options['disable_log']) && $options['disable_log']) {
        echo '<div class="notice notice-error"><p>' . __('Logging is currently disabled. Logs will not be saved, and the log menu is hidden.', 'telegram-bot') . '</p></div>';
    } ?>
    <form method="post" action="options.php">
        <?php settings_fields('wp_telegram_options'); ?>
        <div id="telegram-tabs" style="margin-top:32px;">
            <nav class="nav-tab-wrapper">
                <a href="#tab-general" class="nav-tab nav-tab-active">General</a>
                <a href="#tab-broadcast" class="nav-tab">Posts Broadcast</a>
                <a href="#tab-bot" class="nav-tab">Interactive Chatbot</a>
                <a href="#tab-advanced" class="nav-tab">Advanced</a>
            </nav>
            <div id="tab-general" class="telegram-tab-content" style="display:block;">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="apikey">Plugin API Key</label></th>
                        <td>
                            <input readonly="readonly" id="apikey" type="text" name="wp_telegram_apikey" value="<?php echo get_option('wp_telegram_apikey'); ?>" size="55" />
                            <br><small><?php _e('The unique key for your website: <b>Keep it secret!</b>', 'telegram-bot'); ?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="token">Bot Token</label></th>
                        <td>
                            <input id="token" type="text" name="wp_telegram[token]" value="<?php echo $options['token']; ?>" size="55" autocomplete="off" />
                            <br><small><?php _e('Telegram Bot authentication key. <b>Keep it secret!</b>', 'telegram-bot'); ?></small>
                            <br><small><?php _e('Example format: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">123456:ABCdefGhIJKlmnoPQRst-1234abcd</code></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="username">Bot Username</label></th>
                        <td>
                            <input id="username" type="text" name="wp_telegram[username]" value="<?php echo isset($options['username']) ? $options['username'] : ''; ?>" size="55" />
                            <br><small><?php _e('Telegram Bot username.', 'telegram-bot'); ?></small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">mywebsite_bot</code></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="channelusername"><?php _e('Channel Username', 'telegram-bot'); ?></label></th>
                        <td>
                            <input id="channelusername" type="text" name="wp_telegram[channelusername]" value="<?php echo isset($options['channelusername']) ? $options['channelusername'] : ''; ?>" size="55" />
                            <br><small><?php _e('Insert your channel username (if you want to broadcast).', 'telegram-bot'); ?></small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">@mywebsite</code></small>
                            <br><small><?php _e('The bot must be admin in your channel', 'telegram-bot'); ?></small>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-advanced" class="telegram-tab-content" style="display:none;">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="mode"><?php _e('Connection Mode', 'telegram-bot'); ?></label></th>
                        <td>
                            <select name="wp_telegram[mode]" disabled>
                                <option value="0" selected><?php _e('Telegram WebHooks (required)', 'telegram-bot'); ?></option>
                            </select>
                            <br><small><?php _e('Telegram WebHooks are now required. SSL is mandatory for webhook delivery.', 'telegram-bot'); ?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label>Webhook</label></th>
                        <td>
                            <?php $endpoint = esc_attr(telegram_getapiurl()); ?>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <input id="telegram-endpoint" type="text" readonly value="<?php echo esc_attr($endpoint); ?>" style="flex:1;padding:6px;border:1px solid #ddd;background:#fafafa;" aria-label="Telegram webhook endpoint" />
                                <button id="copy-endpoint" class="button">Copy</button>
                            </div>
                            <p style="font-size:0.9em;margin-top:8px;">
                                <?php _e('This is your private Telegram webhook. Keep it private and make sure it bypasses cache and firewall.', 'telegram-bot'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="zapier"><?php _e('Enable Zapier', 'telegram-bot'); ?></label></th>
                        <td>
                            <input id="zapier" name="wp_telegram[zapier]" type="checkbox" value="1" <?php checked('1', (isset($options['zapier']) && $options['zapier']) ? 1 : 0); ?> />
                            <br><small><?php _e('Enable Zapier integration • Beta •', 'telegram-bot'); ?>
                                <a href="https://zapier.com/developer/invite/26805/1ec54299d4307c0b86b7417d0866ff25/" target="_blank">Click here to get an invite</a>
                            </small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="disable_log"><?php _e('Disable Log', 'telegram-bot'); ?></label></th>
                        <td>
                            <input id="disable_log" name="wp_telegram[disable_log]" type="checkbox" value="1" <?php checked('1', (isset($options['disable_log']) && $options['disable_log']) ? 1 : 0); ?> />
                            <br><small><?php _e('When enabled, the plugin will not save logs to the database, and the log menu will be hidden.', 'telegram-bot'); ?></small>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-broadcast" class="telegram-tab-content" style="display:none;">
                <table class="form-table">
                    <tr valign="top">
                        <td colspan="2">
                            <h3><?php _e('Posts broadcast', 'telegram-bot'); ?></h3>
                            <p><?php _e('You can send post or custom post to Telegram when you publish or edit it.', 'telegram-bot'); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="target"><?php _e('Default content broadcast', 'telegram-bot'); ?></label></th>
                        <td>
                            <select name="wp_telegram[target]">
                                <option><?php _e('Users, Groups, Channel', 'telegram-bot'); ?></option>
                                <option value="1"<?php if (isset($options['target']) && $options['target'] == 1) { echo ' selected="selected"'; } ?>><?php _e('Users', 'telegram-bot'); ?></option>
                                <option value="2"<?php if (isset($options['target']) && $options['target'] == 2) { echo ' selected="selected"'; } ?>><?php _e('Groups', 'telegram-bot'); ?></option>
                                <option value="3"<?php if (isset($options['target']) && $options['target'] == 3) { echo ' selected="selected"'; } ?>><?php _e('Users, Groups', 'telegram-bot'); ?></option>
                                <option value="4"<?php if (isset($options['target']) && $options['target'] == 4) { echo ' selected="selected"'; } ?>><?php _e('Channel', 'telegram-bot'); ?></option>
                                <option value="5"<?php if (isset($options['target']) && $options['target'] == 5) { echo ' selected="selected"'; } ?>><?php _e('All', 'telegram-bot'); ?></option>
                            </select>
                            <br><small><?php _e('Define the post broadcast targeting when you send content (for example posts or manual text).<br>You can also change this behaviour manually.', 'telegram-bot'); ?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="posttemplate"><?php _e('Post Template', 'telegram-bot'); ?></label></th>
                        <td>
                            <textarea id="posttemplate" rows="4" class="widefat" name="wp_telegram[posttemplate]"><?php echo $options['posttemplate']; ?></textarea>
                            <br><small><?php _e('Allowed placeholders: <b>%TITLE% %LINK% %EXCERPT% %CHAT_ID%</b>', 'telegram-bot'); ?></small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">%TITLE% - %LINK%</code></small>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tab-bot" class="telegram-tab-content" style="display:none;">
                <table class="form-table">
                    <tr valign="top">
                        <td colspan="2">
                            <h3><?php _e('Interactive bot', 'telegram-bot'); ?></h3>
                            <p><?php _e('Only use this section if you want to build an interactive bot', 'telegram-bot'); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="wmuser"><?php _e('Start Message', 'telegram-bot'); ?><br><small><?php _e('for users', 'telegram-bot'); ?></small></label></th>
                        <td>
                            <input id="wmuser" type="text" name="wp_telegram[wmuser]" value="<?php echo $options['wmuser']; ?>" size="55" />
                            <br><small><?php _e('Cannot be blank', 'telegram-bot'); ?>.</small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">Hi! Welcome to our bot — send /help to get started.</code></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="bmuser"><?php _e('Bye Message', 'telegram-bot'); ?><br><small><?php _e('for Private Conversations', 'telegram-bot'); ?></small></label></th>
                        <td>
                            <input id="bmuser" type="text" name="wp_telegram[bmuser]" value="<?php echo $options['bmuser']; ?>" size="55" />
                            <br><small><?php _e('Cannot be blank', 'telegram-bot'); ?>.</small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">Goodbye! If you need more help, come back anytime.</code></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="emuser"><?php _e('Error Message', 'telegram-bot'); ?><br><small><?php _e('for users', 'telegram-bot'); ?></small></label></th>
                        <td>
                            <input id="emuser" type="text" name="wp_telegram[emuser]" value="<?php echo isset($options['emuser']) ? $options['emuser'] : ''; ?>" size="55" />
                            <br><small><?php _e('This will be shown when the command doesn\'t exist.', 'telegram-bot'); ?></small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">Sorry, I do not understand that command. Try /help.</code></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="wmgroup"><?php _e('Start Message', 'telegram-bot'); ?><br><small><?php _e('for Groups', 'telegram-bot'); ?></small></label></th>
                        <td>
                            <input id="wmgroup" type="text" name="wp_telegram[wmgroup]" value="<?php echo $options['wmgroup']; ?>" size="55" />
                            <br><small><?php _e('Cannot be blank', 'telegram-bot'); ?>.</small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">Hello everyone! Mention me with @botname to get started.</code></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="keyboard"><?php _e('Keyboard Template', 'telegram-bot'); ?><br><small><?php _e('for users', 'telegram-bot'); ?></small></label></th>
                        <td>
                            <input id="keyboard" type="text" name="wp_telegram[keyboard]" value="<?php echo $options['keyboard']; ?>" size="55" />
                            <br><small><?php _e('Keyboard will be shown below chat messages', 'telegram-bot'); ?>.</small>
                            <br><small><?php _e('Example: ', 'telegram-bot'); ?><code style="background:#f7f7f7;border:1px solid #e1e1e1;padding:2px 6px;margin-left:6px;">1,2,3;4,5,6;Text</code></small>
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
    <style>
        .telegram-settings .nav-tab-wrapper { margin-bottom: 0; }
        .telegram-settings .nav-tab { font-size: 1.1em; }
        .telegram-settings .telegram-tab-content { background: #fff; border: 1px solid #ccd0d4; border-top: none; padding: 24px 32px; border-radius: 0 0 8px 8px; }
        .telegram-settings table.form-table { width: 100%; }
        .telegram-settings th { width: 220px; }
    </style>
    <script>
        (function(){
            const tabs = Array.from(document.querySelectorAll('.nav-tab'));
            const contents = Array.from(document.querySelectorAll('.telegram-tab-content'));
            if (tabs.length) {
                tabs.forEach(tab => {
                    tab.addEventListener('click', function(e) {
                        e.preventDefault();
                        tabs.forEach(t => t.classList.remove('nav-tab-active'));
                        this.classList.add('nav-tab-active');
                        contents.forEach(c => c.style.display = 'none');
                        const sel = this.getAttribute('href');
                        const node = document.querySelector(sel);
                        if (node) node.style.display = 'block';
                    });
                });
            }

            // Copy webhook endpoint
            const copyBtn = document.getElementById('copy-endpoint');
            const endpointInput = document.getElementById('telegram-endpoint');
            if (copyBtn && endpointInput) {
                copyBtn.addEventListener('click', function(e){
                    endpointInput.select();
                    try {
                        document.execCommand('copy');
                        copyBtn.textContent = 'Copied';
                        setTimeout(() => { copyBtn.textContent = 'Copy'; }, 2000);
                    } catch (ex) {
                        // fallback: open in prompt
                        window.prompt('Copy endpoint', endpointInput.value);
                    }
                });
            }
        })();
    </script>
</div>
