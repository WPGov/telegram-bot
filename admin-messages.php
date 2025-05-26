<?php
    add_action('admin_notices', function() {
        if (!function_exists('get_current_screen')) {
            return;
        }
        $screen = get_current_screen();
        if (!$screen) {
            return;
        }
        $notices = [
            'telegram_subscribers' => [
                'type' => 'info',
                'message' => __('List of users who have subscribed to your bot.', 'telegram-bot'),
            ],
            'telegram_groups' => [
                'type' => 'info',
                'message' => __('List of groups where your bot has been added.<br><small>Some <strong>BotFather</strong> actions could be required to get this working.</small>', 'telegram-bot'),
            ],
            'telegram_commands' => [
                'type' => 'info',
                'message' => __('List of active commands of your bot<br><small>You can use the <strong>/command</strong> format as well as <strong>command</strong> (they are different).<br>You can define multiple commands by typing, without spaces, a succession of comma-separated values (example: <strong>/command,command,/command2</strong>)</small>', 'telegram-bot'),
            ],
        ];
        if (isset($screen->post_type) && isset($notices[$screen->post_type])) {
            $notice = $notices[$screen->post_type];
            printf(
                '<div class="notice notice-%s"><p>%s</p></div>',
                esc_attr($notice['type']),
                $notice['message']
            );
        } elseif (isset($screen->taxonomy) && $screen->taxonomy === 'telegram_lists') {
            printf(
                '<div class="notice notice-info"><p>%s</p></div>',
                __('You can create different distribution lists on this page. This can be used for people and groups as well.', 'telegram-bot')
            );
        }
    });
?>