<?php
    function telegram_send_panel() {

        $count = 0;
        $send_to_custom_ids = false;
        echo '<div class="wrap"><h2>'.__('Send a message', 'telegram-bot').'</h2>';
        if (isset($_POST["telegram_new_message"])) {
            $message = $_POST["telegram_new_message"];
            
            if ( isset( $_GET['telegram_post_ids'] ) ) {
                $telegram_post_ids = explode( ',', $_GET['telegram_post_ids']);
                foreach( $telegram_post_ids as $id ) {
                    telegram_sendmessage( get_post_field( 'post_title', $id ), $message);
                }
            } else {
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
            }
            
            echo '<div class="notice notice-success">
            <p>Your message have been sent!</p>
            </div>';
        }
        echo '<form method="post" id="telegram_new_message">';
        if ( isset( $_GET['telegram_post_ids'] ) ) {
            $telegram_post_ids = explode( ',', $_GET['telegram_post_ids']);
            echo '<div class="notice notice-warning"><p>Note: You are sending this message to <b>'.count( $telegram_post_ids ).' subscribers</b></p></div>';
            $send_to_custom_ids = true;
        }
        echo '<textarea name="telegram_new_message" cols="100" rows="10"></textarea><br>';
        $target = $send_to_custom_ids ? 'custom_ids' : telegram_option('target');
        
        if ( !$send_to_custom_ids ) {
            echo __('Send to', 'telegram-bot').':';
            ?>
        <select name="telegram_target">
            <option value="0"><?php _e('Users, Groups, Channel', 'telegram-bot'); ?></option>
            <option value="1"<?php if ($target==1 ) { echo ' selected="selected"'; } ?>><?php _e('Users', 'telegram-bot'); ?></option>
            <option value="2"<?php if ($target==2 ) { echo ' selected="selected"'; } ?>><?php _e('Groups', 'telegram-bot'); ?></option>
            <option value="3"<?php if ($target==3 ) { echo ' selected="selected"'; } ?>><?php _e('Users, Groups', 'telegram-bot'); ?></option>
            <option value="4"<?php if ($target==4 ) { echo ' selected="selected"'; } ?>><?php _e('Channel', 'telegram-bot'); ?></option>
        </select>
        <?php }

        submit_button( __('Send Now', 'telegram-bot'), 'primary');
        echo '</form>';

        echo '</div>';
    }
?>