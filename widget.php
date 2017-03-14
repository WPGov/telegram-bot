<?php
    class telegram_widget extends WP_Widget {

        function __construct() {
            parent::__construct(
                'telegram_widget',
                'Telegram',
                array( 'description' => __( 'Add bot or channel link to your website', 'telegram-bot' ), )
            );
        }

        public function widget( $args, $instance ) {
            echo $args['before_widget'];
            if ( ! empty( $instance['title'] ) ) {
                echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
            }
            ($instance['target']=='bot')?$link = 'https://telegram.me/'.telegram_option('username'):$link='https://telegram.me/'.str_replace('@', '', telegram_option('channelusername'));
            echo '<a href="'.$link.'" style="background: #28a5e7; border-radius: 2px; color: white; padding: 5px 10px; display: inline-block; margin: 10px; font-size: 12px; cursor: pointer;">
            <img src="'.plugins_url().'/telegram-bot/img/telegramiconmini.jpg" style="float:left; width: 24px; margin: 0 10px 0 0;" alt="telegram-icon">'.$instance['text'].'</a>';
            echo $args['after_widget'];
        }

        public function form( $instance ) {
            $text = ! empty( $instance['text'] ) ? $instance['text'] : __( 'Follow us on Telegram', 'telegram-bot' );
            $target = ! empty( $instance['target'] ) ? $instance['target'] : 'bot';
            ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>">
            <label for="<?php echo $this->get_field_id( 'target' ); ?> "><?php _e('Target', 'telegram-bot'); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>">
                <option value="bot" <?php echo ($target=='bot')?'selected':''; ?>><?php _e('Bot', 'telegram-bot'); ?></option>
                <option value="channel" <?php echo ($target=='channel')?'selected':''; ?>><?php _e('Channel', 'telegram-bot'); ?></option>
            </select>
            </p>
            <?php 
        }

        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['text'] = strip_tags($new_instance['text']);
            $instance['target'] = strip_tags($new_instance['target']);
            return $instance;
        }

    }
?>