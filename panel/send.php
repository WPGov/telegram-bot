<?php
    $count = 0;
    echo '<div class="wrap"><h2>'.__('New message', 'telegram-bot').'</h2>';
    if (isset($_POST["telegram_new_message"])) {
        $message = $_POST["telegram_new_message"];
        switch( $_POST["telegram_target"] ) {
            case 0:
            telegram_sendmessagetoall($message);
            break;
            case 1:
            telegram_sendmessage_users($message);
            break;
            case 2:
            telegram_sendmessage_groups($message);
            break;
            case 3:
            telegram_sendmessage_groups($message);
            telegram_sendmessage_users($message);
            break;
            case 4:
            telegram_sendmessage_channel($message);
            break;
        }
        echo '<div class="updated">
        <p>Your message have been sent to <b>' . $count . '</b> subscribers!</p>
        </div>';
    } else {
        echo '<form method="post" id="telegram_new_message"><br><br>';
        echo '<textarea name="telegram_new_message" cols="100" rows="10"></textarea><br>';
        $target = telegram_option('target');
        _e('Target', 'telegram-bot');
        ?>:
        <select name="telegram_target">
            <option value="0"><?php _e('Users, Groups, Channel', 'telegram-bot'); ?></option>
            <option value="1"<?php if ($target==1 ) { echo ' selected="selected"'; } ?>><?php _e('Users', 'telegram-bot'); ?></option>
            <option value="2"<?php if ($target==2 ) { echo ' selected="selected"'; } ?>><?php _e('Groups', 'telegram-bot'); ?></option>
            <option value="3"<?php if ($target==3 ) { echo ' selected="selected"'; } ?>><?php _e('Users, Groups', 'telegram-bot'); ?></option>
            <option value="4"<?php if ($target==4 ) { echo ' selected="selected"'; } ?>><?php _e('Channel', 'telegram-bot'); ?></option>
        </select>
        <br>
        <?php
        submit_button( __('Send Now', 'telegram-bot'), 'primary');
        echo '</form>';
    }
    echo '</div>';
?>

