
<div class="wrap">
    <div class="wrap about__container">

        <div class="about__section is-feature has-accent-background-color">
            <h1>Telegram Bot & Channel</h1>
            <p>Version <?php echo get_option('wp_telegram_version'); ?></p>
        </div>
        <div class=" wp-clearfix"></div>
    
        <div class="about__section has-3-columns" style="text-align:center;">
            <div class="column has-subtle-background-color">
                <span style="font-size:3em;"><?php echo get_option('wp_telegram_dispatches'); ?></span>
                <br>
                messages sent
            </div>
            <div class="column has-subtle-background-color">
                <span style="font-size:3em;"><?php echo wp_count_posts('telegram_subscribers')->publish; ?></span>
                <br>
                subscribers
            </div>
            <div class="column has-subtle-background-color">
                <span style="font-size:3em;"><?php echo wp_count_posts('telegram_groups')->publish; ?></span>
                <br>
                groups
            </div>
        </div>
    </div>
</div>