<?php
    echo '<div class="wrap"><h2>Send a New Message</h2>';
    
    if (isset($_POST["telegram_new_message"])) {
        
        query_posts('post_type=telegram_subscribers&posts_per_page=-1');

        $count = 0;
        while (have_posts()):
            the_post();
            if ( telegram_useractive( get_the_title() ) ) {
                telegram_sendmessage(get_the_title(), $_POST["telegram_new_message"]);
                $count++;
            }
        endwhile;        
        
        echo '<div class="updated">
			<p>Your message have been sent to <b>'.$count.'</b> subscribers!</p>
		</div>';
    } else { 
        echo '<form method="post" id="telegram_new_message">
                <textarea name="telegram_new_message" cols="40" rows="5"></textarea>';

                submit_button('Send NOW', 'primary');

        echo '</form>';
    }
    echo '</div>';
?>