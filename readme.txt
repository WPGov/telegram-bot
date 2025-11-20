=== Telegram Bot & Channel ===
Contributors: Milmor
Version: 4.1.1
Stable tag: 4.1.1
Author: Marco Milesi
Author URI: https://profiles.wordpress.org/milmor/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F2JK36SCXKTE2
Tags: telegram, bot, newsletter, channel, group, broadcast, automation, notifications, autoresponder, webhook, ssl, zapier, integration, marketing, customer-engagement, chatbot, wordpress, classicpress
Requires at least: 4.6
Requires PHP: 7.0
Tested up to: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Supercharge your WordPress site with Telegram! Broadcast posts, automate notifications, and build interactive bots for your users, groups, and channels. Secure, modern, and easy to use. Zapier integration included!

== Description ==

**Telegram Bot & Channel** is the all-in-one solution to connect your WordPress site with Telegram. Instantly broadcast posts, automate notifications, and create interactive bots for your audience. No coding required!

**Key Features:**
- 🤖 **Bot builder:** Create interactive autoresponders for users and groups
- 📣 **Broadcast:** Send posts, pages, or custom messages to your channels, groups, and subscribers
- 🔔 **Automated notifications:** Instantly notify your audience about new content
- 🔗 **Zapier integration:** Connect Telegram with 400+ apps and automate your workflows
- 🔐 **Secure:** All connections use Telegram WebHooks and require SSL for maximum security
- 🛡️ **Modern UI:** Clean, user-friendly admin panels with stats, logs, and quick actions

**Why choose this plugin?**
- 100% free and open source
- No third-party servers: your data stays on your site
- Easy setup: just add your bot token and go
- Developer-friendly: extend with custom PHP actions and filters

== Features ==

**Bot & Broadcast:**
- Send posts, pages, or custom messages with flexible templates
- Schedule broadcasts for future posts
- Use custom keyboards and inline buttons for rich user interaction
- Broadcast to users, groups, supergroups, and channels
- Unlimited autoresponders and command triggers
- View insights about your Telegram audience
- Haversine algorithm for geo-targeted content
- Create custom applications with command variables

**Zapier & Automation:**
- Connect Telegram to 400+ apps (RSS, Instagram, Google Sheets, and more)
- Automate news, weather, social, and IoT notifications
- Easy Zapier invite and setup

**Security & Privacy:**
- All actions protected by WordPress nonces (CSRF protection)
- Only supports secure Telegram WebHooks (SSL required)
- No data sent to third-party servers (except optional Zapier integration)

**Screenshots:**
1. Modern dashboard with stats and recent activity
2. Subscribers list
3. Commands and autoresponders
4. Zapier integration
5. Plugin settings with tabs
6. Dynamic replies and inline buttons
7. Keyboard example
8. Post broadcasting
9. Native Gutenberg support

== Installation ==
1. Upload the `telegram-bot` directory to `/wp-content/plugins/`
2. Activate the plugin in the WordPress admin
3. Go to the Telegram settings page
4. Enter your bot token and configure your preferences
5. Enjoy automated Telegram notifications and bot features!

== Frequently Asked Questions ==

= How do I create a Telegram bot? =
Visit [Telegram’s BotFather](https://core.telegram.org/bots#botfather) and follow the instructions to create a new bot and get your token.

= How do users subscribe? =
Users can start your bot or join your channel/group where the bot is an admin.

= How do I enable Zapier integration? =
Enable Zapier in the plugin settings and follow the invite link for setup instructions.

= Is SSL required? =
Yes, Telegram WebHooks require SSL. Your site must use HTTPS for the plugin to work.

= Can I extend the plugin? =
Yes! Use WordPress hooks and filters to add custom commands, keyboards, and integrations. See the FAQ and code comments for examples.

== Screenshots ==
1. Modern dashboard with stats and recent activity
2. Subscribers list
3. Commands and autoresponders
4. Zapier integration
5. Plugin settings with tabs
6. Dynamic replies and inline buttons
7. Keyboard example
8. Post broadcasting
9. Native Gutenberg support

== Changelog ==

= 4.1.1 - 2025-11-20 =
* [BUGFIX] Security improvements
* [BUGFIX] Minor changes

= 4.1 - 2025-08-29 =
* [IMPROVE] Added "Disable Log" option in the Advanced settings tab.
* [IMPROVE] Improved the structure of the settings page:
* [BUGFIX] Minor changes

= 4.0 20250526 =
* [SECURITY] Added CSRF protection (WordPress nonces) to all sensitive actions (log clear, send message, etc.)
* [SECURITY] Hardened webhook and Zapier endpoints, removed BotPress.org fallback (now only Telegram WebHooks are supported)
* [SECURITY] Improved admin access checks and output escaping throughout the plugin
* [IMPROVE] Redesigned all admin pages for a modern, user-friendly experience (dashboard, log, send message, settings)
* [IMPROVE] Settings page now uses tabs for easier navigation and clarity
* [IMPROVE] Removed all BotPress.org logic and references (service discontinued, SSL is now required)
* [IMPROVE] Inlined and refactored plugin defaults (removed defaults.php)
* [IMPROVE] Refactored admin notices for maintainability
* [IMPROVE] Enhanced log panel with better design and empty state
* [IMPROVE] Enhanced dashboard with stats, recent activity, and quick actions
* [IMPROVE] Improved send message panel with modern UI
* [IMPROVE] Cleaned up and secured Zapier integration
* [IMPROVE] General code cleanup, improved maintainability and best practices
* [IMPROVE] Updated documentation and removed outdated references
* [DEPRECATED] Removed all support for BotPress.org and non-SSL fallback
* [NOTE] This is a major update. Please review your settings and test your integration after updating.

= 3.6.3 20230527 =
* [BUGFIX] Minor changes

= 3.6.2 20230217 =
* [BUGFIX] Minor changes

= 3.6.1 20220927 =
* [BUGFIX] Minor changes

= 3.6 20220927 =
* [NEW] Added function to send message to specific users
* [NEW] Users list refactoring with possibility to edit/delete subscribers
* [NEW] Added possibility to edit subscriber fields
* [NEW] Added user activity counter to check how many input a single subscriber sends to the bot
* [IMPROVE] Better backend UI/UX
* [BUGFIX] Improved Telegram error recognition (e.g., bot kick/block/stop/start)
* [BUGFIX] Code refactoring to boost performance
* [BUGFIX] Improved security and input checks
* [DEPRECATED] Removed "editor" support from telegram_subscribers custom post type 

= 3.5 20220907 =
* This plugin goes green: this update brings various improvements to save energy!
* [NEW] New energy-saving logging method (warning: old logs will be purged)
* [NEW] Added button to view private endpoint (use it to bypass cache/firewall)
* [IMPROVE] Better backend UI/UX
* [BUGFIX] Minor changes
* [BUGFIX] Minor improvements

= 3.4.10 20220819 =
* [BUGFIX] Minor changes

= 3.4.9 20210804 =
* [BUGFIX] Fixed a problem with correct user details update on message received (thanks @sanaconeltantra)
* [BUGFIX] Minor changes

= 3.4.7 20210402 =
* [BUGFIX] Fixed bug with reply_markup "null" - Thanks to Stefano!
* [BUGFIX] Minor changes

= 3.4.6 20210222 =
* [BUGFIX] Minor changes

= 3.4.4 20210222 =
* [BUGFIX] Minor changes

= 3.4.3 20210219 =
* [BUGFIX] Minor changes

= 3.4.2 20210217 =
* [IMPROVE] Added ability to directly send a new manual message after the previous (in send screen)
* [BUGFIX] Solved a bug for error "400 - BAD REQUEST" when sending to a channel and a keyboard was set in options

= 3.4.1 20210205 =
* [IMPROVE] Minor changes

= 3.4 20201210 =
* [IMPROVE] Many function enhancements (faster and more developer flexible)
* [NEW] "Send to ALL" option
* [IMPROVE] Fixed some warnings
* [BUGFIX] Bugfix with Gutenberg default send target
* [IMPROVE] Commands are now "Responders"
* [IMPROVE] Under the hood improvements

= 3.3 20201114 =
* Admin graphic enhancements
* Under the hood improvements

= 3.2.2 20201001 =
* Minor improvements

= 3.2.1 20200909 =
* **Fixed** wrong icon for Gutenberg editor in WP 5.5
* Temporary deactivated "Publish" text alteration in post edit screen (Gutenberg) due to regression

= 3.2 20200908 =
* Added **page** support
* Improved send to Telegram UI with message on publish button
* Fixed bug and compatibility for post_type without custom-fields support declared

= 3.1.2 20200515 =
* Bugfix for particular proxy configurations

= 3.1.1 20200515 =
* Minor improvements

= 3.1 20200430 =
* New welcome screen
* Redesigned options page
* Performance improvements

= 3.0 28.04.2020 =
* Added **Gutenberg** compatibility
* Performance improvements
* New backend design
* Many new features coming soon!

= 2.3 27.12.2019 =
* Anonymous functions to manage post actions are now callable for better third party integration
* Many Thanks to STEFANO - Oratorio Leno (Brescia) ITALY

= 2.2.1 15.06.2019 =
* **Tested** with latest WP version

= 2.2 05.09.2017 =
* **Tested** with WP4.8

= 2.1.1 4.03.2017 =
* Added %CHAT_ID% placeholder for post template (useful for Google Analytics campaign)
* **Fixed** bug with publish_post when using %EXCERPT% (thanks @jsbmand)
* Minor improvements

= 2.0.7 7.02.2017 =
* Fixed bug when parsing content containing "x"

= 2.0.6 13.01.2017 =
* **Fix**: possibile problems with future_post_publish when hooking "telegram_send_post"
* **Improved** perfomance 

= 2.0.4 29.12.2016 =
* Fix: markdown parameter was incorrectly added while sending to channel (error 400)

= 2.0.3 21.12.2016 =
* Added filters to publish_post and publish_future_post to allow plugin's customizations via actions
* Fixed bug that printed a false error "incorrect parameters due to: HTTP/1.1 200 OK" in log
* Fixed bug causing errors while sending to groups in some cases

= 2.0.1 16.12.2016 =
* Fixed bug with widget channel link - Thanks @aghorbanmehr
* Improved widget style
* Improved main panel
* Added localhost warning in plugin options (plugin can't receive messages in offline environments)
* Improved readme.txt

= 2.0 14.12.2016 =
* Follow our new Telegram channel [telegram.me/botpressorg](https://telegram.me/botpressorg)
* Tested with WP 4.7
* This is a major change that requires attention: info [www.botpress.org/?p=2021](https://www.botpress.org/?p=2021)
* New "bypass" platform for non-SSL users: [www.botpress.org](https://www.botpress.org). Account registration no-longer required because it's now done automatically by the plugin (as **opt-in** service)
* Added **inline buttons** support
* Added targeting system to send messages differently between users/groups/channel
* Added widget to let users follow your bot/channel
* Improved speed, filters and actions
* Bugfixes and UI improvements

= 1.8.3 5.12.2016 =
* Bugfix
* New PHP filters for subscriber and group views (check source code to find more)

= 1.8.1 16.11.2016 =
* Regression bugfix: new broadcast system didn't work with custom post types
* Minor performance boost

= 1.8 15.11.2016 =
* Posts broadcast completely rewritten due to occasional bugs
* Scheduled posts broadcast added
* Version 2.0 in progress: stay tuned!

= 1.7.1 16.09.2016 =
* Improved telegram_sendmessage() with ability to define custom keyboards via php
* Added function to build a reply_markup that can be used as third parameter in telegram_sendmessage()
* More dev-info in our faqs!

= 1.7 14.09.2016 =
* Added php function to get and save incoming files. More info in our faqs!
* We're planning a **free** server upgrade for non-SSL users. Stay tuned: the best is yet to come!
* Minor changes

= 1.6.1 27.08.2016 =
* Added nopaging => true in "telegram_sendmessagetoall" function to force website post_limit override

= 1.6 26.08.2016 =
* Added harvesine algorithm for radial geolocation. Check the faqs for more informations
* Added a complete debug mode for developers to extend the plugin via custom fields. Check the faqs for more informations
* Readme changes

= 1.5.2 25.08.2016 =
* Fix for some persian environments
* Performance improvements
* Switched debug_mode trigger. Now you have to enable WP_DEBUG if you want to fire the plugin debug mode.

= 1.5.1 23.08.2016 =
* Critical fix for "Insert_PHP" parsing (and similar) caused by oembed conflict (thanks @websurfertech)

= 1.5 23.08.2016 =
* Added instant send when publishing new content with configurable template
* This version changes the sendmessage function because WordPress now turned post links to title automatically.
* Please test your dynamic commands if you extended the plugin via php
* Minor improvements

= 1.4.2 17.08.2016 =
* Minor bugfix
* Minor improvements
* WP 4.6 compatibility check

= 1.4.1 06.08.2016 =
* Fixed error while parsing placeholders for supergroup

= 1.4 07.07.2016 =
* Timeout prevention while sending to many users (Telegram doesn't support bulk actions)
* Please note that Telegram broadcasting is better with channels. You can try also indoona, [indoona](https://wordpress.org/plugins/indoona-connect/), another open chat (made in Italy) that is better and faster when sending multiple messages at once. indoona allows you to keep your followers and fans always up to date with your WordPress site directly from their chat!
* Bugfix in parsing
* Entries are now deleted instead of being deactivated (timeout prevention)
* Old entries will be deleted when approriate error code is received from Telegram (as before when deactivating)

= 1.3.18 6.07.2016 =
* Improved message_id check to avoid Telegram loops

= 1.3.17 21.05.2016 =
* Removed message_id check due to Telegram changes - investigating now
* Fixed reply_markup creation due to Telegram changes - should be correct now

= 1.3.16 22.04.2016 =
* Bugfix for supergroups message_id

= 1.3.15 03.04.2016 =
* Added supergroup support
* Tested with WP4.5

= 1.3.14 26.02.2016 =
* another bugfix for persian WP Jalali compatibility

= 1.3.13 26.02.2016 =
* Fixed bug with WP-Jalali when persian numbers conversion enabled

= 1.3.12 21.02.2016 =
* Added return values (true/false) to telegram_sendmessage($args)

= 1.3.11 15.02.2016 =
* Fixed wrong version on main panel

= 1.3.10 14.02.2016 =
* Minor improvements
* Performance improvements

= 1.3.9 13.02.2016 =
* Fixed some php notices

= 1.3.8 05.02.2016 =
* Added check to avoid empty message departure

= 1.3.7 02.02.2016 =
* Added message_id check for loop prevention
* Added sleep() function to prevent Telegram API limits when sending bulk messages

= 1.3.6 01.02.2016 =
* Performance improvements and loop prevention

= 1.3.5 28.01.2016 =
* Now **manual send** detects inactive users and deactive theme.
* Minor changes
* Fixed bug with sending messages to groups

= 1.3.4 24.01.2016 =
* Added hackable **telegram_parse_location** filter

= 1.3.3 13.01.2016 =
* Better scalability for admin columns
* Minor improvements

= 1.3.2 13.01.2016 =
* Fixed columns.php encoding
* Minor changes

= 1.3 8.01.2016 =
* Added custom keyboards for commands
* Added location services - see [wptele.ga](https://wptele.ga) documentation

= 1.2 7.01.2016 =
* Added custom keyboard
* Added commands alias (just type them comma-separated in the title field)
* Minor improvements

= 1.1.1 7.01.2016 =
* Minor changes

= 1.1 26.12.2015 =
* Added channel support
* Added new translation system. Please contribute here: [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/telegram-bot) 
* Minor improvements

= 1.0.2 21.11.2015 =
* Fixed log DEBUG being incorrectly activated
* Minor improvements to log system
* Various improvements

= 1.0 19.11.2015 =
* Various bugfixs and improvements
* Added full Groups support (bot can now be added to groups)
* Added separate view for groups
* Improved Users panel
* Added customizable /start and /stop message (for groups too)
* Added notices in admin screen

= 0.13 11.11.2015 =
* Now **/command** and **/command test** call the same function. Not for **/commandtest**
* This allows to build more advanced functions with parameters!

= 0.12 11.11.2015 =
* Added developer function telegram_get_data_array()
* Readme changes
* Minor changes

= 0.11 09.11.2015 =
* Fixed bug in some sites when having more than 10 commands (bypass global site query limit)
* Now if a user change/add/remove First Name, Last Name, Username in his Telegram the list will reflect changes
* Better Main Panel with RSS
* Added dispatches counter
* New style for inactive users in Subscribers list
* Minor improvements

= 0.10 24.10.2015 =
* Added shortcode parsing. This includes php parsing if apposite plugin is installed - eg [Insert Php](https://wordpress.org/plugins/insert-php/)
* Bugfixes
* Minor improvements

= 0.9 23.09.2015 =
* Added Zapier integration (beta) - see faq for details
* Minor changes
* Added possibility to use markdown (BOLD, ITALIC, URL)

= 0.8 15.09.2015 =
* Subscribers list changes
* Added **/stop** support
* Bugfixes + improvements

= 0.7.3 - 14.09.2015 =
* Improved send message function

= 0.7.2 - 13.09.2015 =
* Commands now are case-insensitive
* Fixed bug for post thumbnail not being received

= 0.7.1 - 13.09.2015 =
* Compatibility fix for PHP5.3

= 0.7 - 13.09.2015 =
* Added **Send Message** panel
* Fixed XSS vulnerability (thanks to Roman Ananyev)

= 0.1 - 07.09.2015 =
* First Commit
