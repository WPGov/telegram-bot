<?php

function t_notices() {

    global $current_screen;
    
    if ( 'telegram_subscribers' == $current_screen->post_type  ) {
        echo '
        <div class="updated">
            <p>List of users who have subscribed to your bot.</p>
        </div>';
    } else if ( 'telegram_groups' == $current_screen->post_type  ) {
        echo '
        <div class="updated">
            <p>List of groups where your bot has been added.<br>
            <small>Some <strong>BotFather</strong> actions could be required to get this working.</small></p>
        </div>';
    } else if ( 'telegram_commands' == $current_screen->post_type  ) {
        echo '
        <div class="updated">
            <p>List of active commands of your bot<br>
            <small>You can use the <strong>/command</strong> format as well as <strong>command</strong> (they are different).
            <br>You can define multiple commands by typing, without spaces, a succession of comma-separated values (example: <strong>/command,command,/command2</strong>)</small></p>
        </div>';
    } else if ( 'telegram_lists' == $current_screen->taxonomy ) {
        echo '
        <div class="updated">
            <p>You can create different distribution lists in this page. This can be used for people and groups as well.</p>
        </div>';
    }
} add_action( 'admin_notices', 't_notices' );

?>