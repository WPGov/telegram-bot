<?php
    function telegram_send_panel() {

        $count = 0;
        $send_to_custom_ids = false;
        echo '<div class="wrap telegram-send-panel" style="max-width:700px;margin:auto;">';
        echo '<h1 style="margin-bottom:24px;">' . __('Send a Message', 'telegram-bot') . '</h1>';
        if (isset($_POST["telegram_new_message"])) {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'telegram_send_message')) {
                wp_die(__('Security check failed.','telegram-bot'));
            }
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
            
            echo '<div class="notice notice-success" style="margin-bottom:24px;"><p>' . __('Your message has been sent!', 'telegram-bot') . '</p></div>';
        }
        echo '<form method="post" id="telegram_new_message" style="background:#fff;padding:32px 32px 24px 32px;border-radius:10px;box-shadow:0 2px 8px #0001;">';
        wp_nonce_field('telegram_send_message');
        if ( isset( $_GET['telegram_post_ids'] ) ) {
            $telegram_post_ids = explode( ',', $_GET['telegram_post_ids']);
            echo '<div class="notice notice-warning" style="margin-bottom:16px;"><p>' . __('Note: You are sending this message to', 'telegram-bot') . ' <b>' . count( $telegram_post_ids ) . ' ' . __('subscribers', 'telegram-bot') . '</b></p></div>';
            $send_to_custom_ids = true;
        }
        echo '<label for="telegram_new_message" style="font-weight:bold;font-size:1.1em;">' . __('Message', 'telegram-bot') . '</label><br>';
        echo '<textarea name="telegram_new_message" id="telegram_new_message" cols="80" rows="7" style="width:100%;margin-bottom:18px;font-size:1.1em;"></textarea><br>';
        $target = $send_to_custom_ids ? 'custom_ids' : telegram_option('target');
        
        if ( !$send_to_custom_ids ) {
            echo '<label for="telegram_target" style="font-weight:bold;">' . __('Send to', 'telegram-bot') . ':</label> ';
            echo '<select name="telegram_target" id="telegram_target" style="margin-bottom:18px;">';
            echo '<option value="0"' . ($target == 0 ? ' selected' : '') . '>' . __('Users, Groups, Channel', 'telegram-bot') . '</option>';
            echo '<option value="1"' . ($target == 1 ? ' selected' : '') . '>' . __('Users', 'telegram-bot') . '</option>';
            echo '<option value="2"' . ($target == 2 ? ' selected' : '') . '>' . __('Groups', 'telegram-bot') . '</option>';
            echo '<option value="3"' . ($target == 3 ? ' selected' : '') . '>' . __('Users, Groups', 'telegram-bot') . '</option>';
            echo '<option value="4"' . ($target == 4 ? ' selected' : '') . '>' . __('Channel', 'telegram-bot') . '</option>';
            echo '</select><br>';
        }
        echo '<button type="submit" class="button button-primary" style="font-size:1.1em;padding:8px 32px;">' . __('Send Now', 'telegram-bot') . '</button>';
        echo '</form>';
        echo '<style>.telegram-send-panel textarea { font-family: inherit; } .telegram-send-panel .notice-success { border-left: 4px solid #46b450; } .telegram-send-panel .notice-warning { border-left: 4px solid #ffb900; }</style>';
        echo '</div>';
    }
?>