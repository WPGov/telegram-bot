# Telegram Bot & Channel

[![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/telegram-bot)](https://wordpress.org/plugins/telegram-bot/)
[![Tested up to](https://img.shields.io/wordpress/plugin/tested/telegram-bot)](https://wordpress.org/plugins/telegram-bot/)
[![Active installs](https://img.shields.io/wordpress/plugin/installs/telegram-bot)](https://wordpress.org/plugins/telegram-bot/)
[![License: GPL v2](https://img.shields.io/badge/license-GPLv2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

Broadcast your WordPress content to Telegram channels, groups and subscribers, and build interactive bots with autoresponders, keyboards and Zapier.

👉 **Download:** [wordpress.org/plugins/telegram-bot](https://wordpress.org/plugins/telegram-bot/)

## Features

- 📣 **Broadcast** posts, pages or custom messages to channels, groups, supergroups and bot subscribers
- ⏰ **Automatic notifications** on publish (also for scheduled posts), with a configurable template
- 🤖 **Responders**: unlimited commands and autoresponders, with custom keyboards and inline buttons
- ✍️ **Block editor** integration to choose whether and where each post is sent
- 👥 **Subscribers & groups** management, with per-user activity counters
- 📍 **Location-aware** replies (Haversine helpers)
- 🔗 **Zapier** integration and a sidebar **widget**
- 🔐 Secure Telegram WebHooks (HTTPS), nonces and capability checks; no third-party servers

## Requirements

- WordPress 4.6+ (tested up to 7.2)
- PHP 7.0+
- HTTPS, so Telegram can deliver WebHooks to your site

## Quick start

1. Install and activate the plugin.
2. Create a bot with [@BotFather](https://t.me/BotFather) and copy its token.
3. Go to **Telegram → Settings**, paste the token and save.
4. Optional: add the bot as administrator of your channel and enter the channel `@username`.

## For developers

```php
// Send a message
telegram_sendmessage( $chat_id, 'Hello!' );
telegram_sendmessagetoall( 'Message for everyone' );
telegram_sendmessage_channel( 'Message for the channel' );

// Reply to a custom command
add_action( 'telegram_parse', function( $user_id, $text ) {
    if ( $text === '/ping' ) {
        telegram_sendmessage( $user_id, 'pong' );
    }
}, 10, 2 );
```

**Actions:** `telegram_parse`, `telegram_parse_location`, `telegram_parse_photo`, `telegram_parse_document`, `telegram_send_post_before`, `telegram_send_post`
**Filters:** `telegram_get_reply_markup_filter`, `telegram_subscribers_register_capabilities`, `telegram_groups_register_capabilities`

## Contributing

Issues and pull requests are welcome. Licensed under [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
