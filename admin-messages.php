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
                'message' => esc_html__('This page lists all users who have subscribed to your Telegram bot.', 'telegram-bot'),
            ],
            'telegram_groups' => [
                'type' => 'info',
                'message' => wp_kses(
                    __('This page lists all groups where your bot has been added.<br><small>Some tuning via <strong>BotFather</strong> may be required to enable this functionality.</small>', 'telegram-bot'),
                    [
                        'br' => [],
                        'small' => [],
                        'strong' => []
                    ]
                ),
            ],
            'telegram_commands' => [
                'type' => 'info',
                'message' => wp_kses(
                    __('This page allows you to define commands for your Telegram bot. For example, you can set <strong>/hello</strong> to respond with "Hello!" when a user sends that command in chat.<br><small>Commands can return text, images, or trigger additional actions.<br>You can define multiple commands by entering them as a comma-separated list without spaces (e.g., <strong>/hello,hello,/start</strong>).</small>', 'telegram-bot'),
                    [
                        'br' => [],
                        'small' => [],
                        'strong' => []
                    ]
                ),
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
                esc_html__('Use this page to create distribution lists for users and groups.', 'telegram-bot')
            );
        }
    });
?>