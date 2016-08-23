<?php
    echo '
    <div class="wrap">
        <div id="welcome-panel" class="welcome-panel" style="padding-bottom: 20px;">
            <div class="welcome-panel-content">
                            <center><a href="//wptele.ga" target="_blank"><img src="' .plugins_url('img/telegrambot.png',__FILE__). '" width="30%" /></a>
                            <br><big>v<strong>'.get_option('wp_telegram_version').'</strong></big></center>
            </div>
        </div>
        <div id="welcome-panel" class="welcome-panel" style="padding-bottom: 20px;">
                <div class="welcome-panel-column-container">';
    include_once( ABSPATH . WPINC . '/feed.php' );
    $rss = fetch_feed( 'http://wptele.ga/feed/' );
    $maxitems = 0;
    if ( ! is_wp_error( $rss ) ) :
        $maxitems = $rss->get_item_quantity( 3 ); 
        $rss_items = $rss->get_items( 0, $maxitems );
    endif;
?>

    <?php if ( $maxitems == 0 ) : ?>
        <li><?php _e( 'Cannot reach the feed', 'telegram-bot' ); ?></li>
    <?php else : ?>
        <?php foreach ( $rss_items as $item ) : ?>
            <div style="float:left;width:33%;text-align: center;">
                <a target="_blank" href="<?php echo esc_url( $item->get_permalink() ); ?>"
                    title="<?php printf( __( 'Posted %s', 'telegram-bot' ), $item->get_date('j F Y | g:i a') ); ?>">
                    <?php echo esc_html( $item->get_title() ); ?>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

<?php
    echo '
            </div>
        </div>
        <div id="welcome-panel" class="welcome-panel" style="padding-bottom: 20px;">
                <div class="welcome-panel-column-container">
                    <div class="welcome-panel-column" style="text-align:center;padding-top:50px; ">
                    <a class="button button-primary button-hero load-customize hide-if-no-customize" href="admin.php?page=telegram_send">Send a Message</a>
                    </div>
                <div class="welcome-panel-column">
                <h4>Stats</h4>
                <ul>
                    <li><a href="admin.php?page=telegram_log" class="welcome-icon dashicons-email">'.get_option('wp_telegram_dispatches').' messages dispatched</a></li>
                    <li><a href="edit.php?post_type=telegram_subscribers" class="welcome-icon dashicons-admin-users">'.wp_count_posts('telegram_subscribers')->publish.' people subscribed</a></li>
                    <li><a href="edit.php?post_type=telegram_subscribers" class="welcome-icon dashicons-groups">'.wp_count_posts('telegram_groups')->publish.' groups subscribed</a></li>
                    <li><a href="edit.php?post_type=telegram_commands" class="welcome-icon dashicons-admin-settings">'.wp_count_posts('telegram_commands')->publish.' commands</a></li>
                </ul>
            </div>
            <div class="welcome-panel-column welcome-panel-last">
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.4&appId=251544361535439";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, \'script\', \'facebook-jssdk\'));</script>
                <div class="fb-page" data-href="https://www.facebook.com/WPGov" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/WPGov"><a href="https://www.facebook.com/WPGov">WPGov.it</a></blockquote></div></div>
                </div>
            </div>
        </div>
    </div>';
?>