<?php
// Security: Only allow admins to view this page
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Fetch stats
$version = esc_html(get_option('wp_telegram_version'));
$dispatches = number_format_i18n(intval(get_option('wp_telegram_dispatches')));
$subscribers = number_format_i18n(wp_count_posts('telegram_subscribers')->publish);
$groups = number_format_i18n(wp_count_posts('telegram_groups')->publish);

// Fetch recent log entries (last 5)
$log = get_option('wp_telegram_log');
$recent_log = is_array($log) ? array_slice($log, 0, 5) : array();

// Fetch plugin settings link
$settings_url = admin_url('admin.php?page=telegram_settings');

// Fetch log page link
$log_url = admin_url('admin.php?page=telegram_log');

?>
<div class="wrap telegram-dashboard" style="max-width:900px;margin:auto;">
    <div class="telegram-header" style="display:flex;align-items:center;justify-content:space-between;padding:32px 0 16px 0;">
        <div>
            <h1 style="margin:0;font-size:2.5em;">🤖 Telegram Bot & Channel</h1>
            <span style="color:#888;font-size:1.1em;">v<?php echo $version; ?></span>
        </div>
        <div>
            <a href="<?php echo esc_url($settings_url); ?>" class="button button-primary" style="font-size:1.1em;">⚙️ <?php _e('Settings', 'telegram-bot'); ?></a>
        </div>
    </div>
    <div class="telegram-stats" style="display:flex;gap:32px;justify-content:space-between;margin:32px 0;">
        <div class="telegram-stat-card" style="flex:1;background:#f6f8fa;padding:32px 0;border-radius:12px;text-align:center;box-shadow:0 2px 8px #0001;">
            <div style="font-size:2.5em;color:#2271b1;font-weight:bold;"><?php echo $dispatches; ?></div>
            <div style="color:#666;margin-top:8px;">Messages Sent</div>
        </div>
        <div class="telegram-stat-card" style="flex:1;background:#f6f8fa;padding:32px 0;border-radius:12px;text-align:center;box-shadow:0 2px 8px #0001;">
            <div style="font-size:2.5em;color:#46b450;font-weight:bold;"><?php echo $subscribers; ?></div>
            <div style="color:#666;margin-top:8px;">Subscribers</div>
        </div>
        <div class="telegram-stat-card" style="flex:1;background:#f6f8fa;padding:32px 0;border-radius:12px;text-align:center;box-shadow:0 2px 8px #0001;">
            <div style="font-size:2.5em;color:#d63638;font-weight:bold;"><?php echo $groups; ?></div>
            <div style="color:#666;margin-top:8px;">Groups</div>
        </div>
    </div>
    <div class="telegram-actions" style="display:flex;gap:16px;justify-content:flex-end;margin-bottom:24px;">
        <a href="<?php echo esc_url($log_url); ?>" class="button button-secondary">📝 <?php _e('View Log', 'telegram-bot'); ?></a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=telegram_send')); ?>" class="button button-primary">✉️ <?php _e('Send Message', 'telegram-bot'); ?></a>
    </div>
    <div class="telegram-recent-log" style="background:#fff;border-radius:10px;box-shadow:0 1px 4px #0001;padding:24px;">
        <h2 style="margin-top:0;font-size:1.3em;"><?php _e('Recent Activity', 'telegram-bot'); ?></h2>
        <?php if (!empty($recent_log)) : ?>
            <table class="widefat striped" style="margin-top:12px;">
                <thead>
                    <tr>
                        <th><?php _e('Type', 'telegram-bot'); ?></th>
                        <th><?php _e('Date', 'telegram-bot'); ?></th>
                        <th><?php _e('Author', 'telegram-bot'); ?></th>
                        <th><?php _e('Description', 'telegram-bot'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_log as $line) : ?>
                        <tr>
                            <td><span style="font-weight:bold;color:#2271b1;"><?php echo isset($line[0]) ? esc_html($line[0]) : ''; ?></span></td>
                            <td><?php echo isset($line[1]) ? esc_html($line[1]) : ''; ?></td>
                            <td><?php echo isset($line[2]) ? esc_html($line[2]) : ''; ?></td>
                            <td><?php echo isset($line[3]) ? esc_html($line[3]) : ''; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <div style="color:#888;text-align:center;padding:24px 0;">No recent activity found.</div>
        <?php endif; ?>
    </div>
    <div class="telegram-quick-actions" style="margin-top:32px;display:flex;gap:24px;">
        <div style="flex:1;background:#f6f8fa;padding:20px 24px;border-radius:10px;">
            <h3 style="margin-top:0;">Subscriber Search</h3>
            <form method="get" action="<?php echo esc_url(admin_url('edit.php')); ?>">
                <input type="hidden" name="post_type" value="telegram_subscribers" />
                <input type="search" name="s" style="width:100%;margin-bottom:8px;" placeholder="Search subscribers by name or ID..." />
                <button type="submit" class="button">Search</button>
            </form>
        </div>
    </div>
    <style>
        .telegram-dashboard h1, .telegram-dashboard h2, .telegram-dashboard h3 { font-family: 'Segoe UI', 'Arial', sans-serif; }
        .telegram-dashboard .button-primary { background:#2271b1;border-color:#2271b1; }
        .telegram-dashboard .button-primary:hover { background:#1a5a8a;border-color:#1a5a8a; }
        .telegram-dashboard .button-danger { background:#d63638;border-color:#d63638;color:#fff; }
        .telegram-dashboard .button-danger:hover { background:#a00;border-color:#a00; }
    </style>
</div>