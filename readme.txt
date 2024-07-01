=== Telegram Bot & Channel ===
Contributors: Milmor
Version:	3.8
Stable tag:	3.8
Author:		Marco Milesi
Author URI:   https://profiles.wordpress.org/milmor/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F2JK36SCXKTE2
Tags: telegram, bot, newsletter, channel, group, automatic, stream, classicpress
Requires at least: 3.8
Requires PHP: 5.6
Tested up to: 6.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send your posts to Telegram and build an interactive bot. Zapier integrated!

== Description ==

This  plugin allows you to accomplish a variety of tasks, including teaching, playing, searching, broadcasting, reminding, connecting, and integrating with your services.

With this powerful bot builder, you can: 

🤖 **Build a bot**: create interactive autoresponders for users and groups
📣 **Broadcast to a channel**: utilize the broadcast feature to send messages to your channels and bot subscribers

https://youtu.be/8fckoWSmAks

= Bot features =

Enhance your content distribution strategy with these **free** advanced features:

📰 Send your content (post, page or custom messages) with templates
📅 Support for scheduled post broadcast
⌨️ Utilize **keyboards** and **inline buttons** for enhanced user engagement
💬 Broadcast to various channels including chats, groups, supergroups, and channels
↩️ Create unlimited autoresponders
📊 View insights about users and groups subscribed to your bot
📡 Haversine algorithm to get users' location and provide geo-focused content
🎨 Create custom applicatons with **/$command $var1 $var2** format for custom application creation
💡 **[Zapier](https://zapier.com)** integration to ensure seamless connectivity with your other tools

= Channel features =
📰 Send your content (post, page or custom messages) with configurable templates
📅 Support for scheduled post broadcast
💡 **[Zapier](https://zapier.com)** integration

**Note:** your bot must be administrator of your channel for sending messages

🔐 Every connection relies on secure webhooks for maximum security. Telegram requires **SSL** to manage a Telegram Bot. If you don't have it, just choose the free opt-in service [botpress.org](https://botpress.org) in options (the feature will send some data to our server).

= Zapier and IoT features = 
Zapier makes it easy to automate tasks between web apps. For example:

* send a news published on a website (based on RSS)
* send the weather to your subscribers, every day
* inform users when you upload an image on Instagram
* and much more… With 400+ Zapier Apps supported!

https://www.youtube.com/watch?v=14aEV0_FHFk

= DEMO =
* **[CosenzApp_bot](http://telegram.me/CosenzApp_bot)** (italian) - Guide for Cosenza city
Want to showcase your work? contact us!

== Installation ==
This section describes how to install the plugin and get it working.

1. Upload `telegram-bot` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Telegram settings page
4. Go through the steps and hit update!

== Frequently Asked Questions ==
More documentation is available on [www.botpress.org/docs](https://www.botpress.org/docs)

= How do I create a bot? =
[www.botpress.org/docs/telegram/how-to-create-a-bot](https://www.botpress.org/docs/telegram/how-to-create-a-bot/)

= How can i let users subscribe? =
[www.botpress.org/docs/telegram/how-do-users-subscribe](https://www.botpress.org/docs/telegram/how-do-users-subscribe/)

= What is Zapier and how do i integrate it? =
[www.botpress.org/docs/telegram/zapier-integration](https://www.botpress.org/docs/telegram/zapier-integration/)

= How to enable debug mode? =
If you are a developer, or just want a more complete "Telegram > Log" enable WP_DEBUG mode.
The plugin debug mode also allows to explore Telegram users and groups as standard posts. This let you to check custom fields for each users and modify them in real time. You'll notice a new column (= Telegram id for the user) in Subscribers and Groups page.
We don't suggest to keep WP_DEBUG if not for testing purposes.

= How to make dynamic replies? (PHP required) =
The best way to integrate PHP code is to build a custom integration plugin, but you can also add PHP to /$commands directly in your WordPress admin dashboard using the [Insert Php](https://wordpress.org/plugins/insert-php) plugin.

In case you want to scale and choose the first option, you can create a new file called **telegram-bot-custom.php** and upload it to wp-content/plugins.

The following example, once activated in the plugins list, will reply to `/command`:

`<?php
/*
Plugin Name: Telegram Bot & Channel (Custom)
Description: My Custom Telegram Plugin
Author: My name
Version: 1
*/

add_action('telegram_parse','telegramcustom_parse', 10, 2);

function telegramcustom_parse( $telegram_user_id, $text ) {
    $plugin_post_id = telegram_getid( $telegram_user_id );

    if ( !$plugin_post_id ) {
        return;
    }

	/*
	Here is the dynamic processing and how to reply.
	You can:
	- use if, switch and everything that works in php
	- check if $text is made of multiple words (create an array from $text)
	- customize and code other actions (ex. create WordPress post is $telegram_user_id is your id)
	*/

    if ( $text == '/command') {
        telegram_sendmessage( $telegram_user_id, 'Oh, yes you typed /command');
    }

    return;
}

?>`

= How to set up dynamic keyboards? =
You can send custom keyboards directly in php. Every keyboard can be set only when you send a message, and is kept in the client side until another keyboard is sent (in another message). You can also change this behaviour by setting the $one_time_keyboard true or false.

`telegram_sendmessage( $telegram_user_id, 'Hello from the other side!'); //Message with no keyboard (or with default one if set in plugin options)
telegram_sendmessage( $telegram_user_id, 'Hello from the other side!', telegram_build_reply_markup( '11,12,13;21,22', true )); //Message with custom keyboard`

Here is the details of telegram_build_reply_markup (an array is returned):
`telegram_build_reply_markup(
 '11,12,13;21,22', //The keyboard template (eg. 2 row, 3 columns for the first one and two columns for the second one
 true, // $one_time_keyboard (optional) (default false = kept until a new keyboard is sent) (true = kept until the user send something to the bot)
 true // $resize_keyboard (optional) (default true)
);`

You can also alter keyboards for commands defined in the **admin area**. Start from the previous custom plugin created, and add the following filter:

`add_filter( 'telegram_get_reply_markup_filter', 'telegram_get_reply_markup_filter_custom', 1 );

function telegram_get_reply_markup_filter_custom( $id ) {
    if ( $id ) {
        switch ( $id ) {
            case 7: //Your command ID (found in the url while editing the command)
                telegram_log('####', $id, 'custom keyboard'); //Useful for debug
                return array(
                    'keyboard' => array(array('Top Left', 'Top Right'),array('Bottom')),
                    'resize_keyboard' => true, //true or false (suggested: true)
                    'one_time_keyboard' => true //true or false
                    );            
            default:
                return;
        }
    }
}`

= How to get user location? =
It's easy, with harvesine algorithm (one-point radius) or standard geolocation (4-points).
These snippets only cover the harvesine algorithm, that is simple and supported by the plugin. To use the standard 4-points geolocation it's enough to do some php-calc with basic if-then structures.

You can start from the previous custom plugin created, and add the following action:

`add_action('telegram_parse_location','telegramcustom_c_parse_location', 10, 3);

function telegramcustom_c_parse_location ( $telegram_user_id, $lat, $long  ) {

  if ( telegram_location_haversine_check ( 45.85, 9.70, $lat, $long, 20 ) ) {
    telegram_sendmessage( $telegram_user_id, 'Inside the radius');
  }

}`

The examples sends a "Inside the radius" message when the user is inside the **20-meters** radius centered in 45.85 Lat, 9.70 Long.

You have two developer functions to use:

`//Check if point is within a distance (max_distance required)
$boolean = telegram_location_haversine_check ( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $max_distance, $min_distance = 0, $earthRadius = 6371000);

//Calculate the distance
$int = telegram_location_haversine_distance ( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000);`

The first function returns a boolean (true/false) depending on given parameters. Please note that $min_distance and $earthRadius are optional.
The second one returns a int (in meters) of the distance. $earthRadius optional.

Both the functions calculates distances on meters. If you want another type of result, just change the $earthRadius.

= How to get user photos? =
We've written simple functions to let developers build everything.
Photos are saved in **/wp-content/uploads/telegram-bot/'.$plugin_post_id.'/'.$file_name** where $plugin_post_id is the custom post type id associated with the Telegram subscription (ex. '24') and $file_name is time().$extension

`<?php
/*
Plugin Name: Telegram Bot & Channel (Custom)
Description: My Custom Telegram Plugin
Author: My name
Version: 1
*/

add_action('telegram_parse_photo','telegramcustom_parse_photo', 10, 2);

function telegramcustom_parse_photo ( $telegram_user_id, $photo  ) {

 $plugin_post_id = telegram_getid( $telegram_user_id );

 if ( !$plugin_post_id ) {
  return;
 }

 /*
  Here is the dynamic processing and how to reply.
  You can:
   - use if, switch and everything that works in php
   - check if $text is made of multiple words (create an array from $text)
   - customize and code other actions (ex. create WordPress post is $telegram_user_id is your id)
 */
 
 /*
   $photo[2]['file_id'] is only one of available sizes. You should make sure that this size exist, or check for another size.
   $photo[1]['file_id'] has lower resolution
 */
 $url = telegram_download_file( $telegram_user_id, $photo[2]['file_id'] ); //Fetch and save photo to your server
 
 if ( $url ) { //$url is the local url because photo is already saved

  //You can save the entry in your db
  global $wpdb;
  $arr = array( 'telegram_id' => $telegram_user_id, 'plugin_post_id' => $plugin_post_id, 'url' => $url );
  
  $wpdb->insert(
   $wpdb->prefix . 'your_table_name_that_must_already_exist', $arr, array( '%s' )
  );

  //Or save it as custom field and use it for a Finite State Machine
  update_post_meta( $plugin_post_id, 'telegram_custom_last_photo_received', $url );

  telegram_sendmessage( $telegram_user_id, 'Photo received. Thank you!');
}

?>`

Another example, that is a "emergency bot" created for the mid-italy earthquake (24 august 2016) is available on [GitHub](https://github.com/milesimarco/terremotocentroitalia-bot-telegram/)

== Screenshots ==
1. Plugin dashboard
2. Subscribers list
3. Commands list
4. Autoresponders
5. Zapier integration
6. plugin options
7. Dynamic repliles and inline buttons example from [IcBrendola_bot](http://telegram.me/IcBrendola_bot)
8. Keyboard example from [CosenzApp_bot](http://telegram.me/CosenzApp_bot)
9. Post broadcasting (all post types)
10. Native Gutenberg support

== Changelog ==

= 3.7 20231020 =
* [NEW] Added ability to selectively send message to subscribed groups
* [BUGFIX] Fixed critical bug when sending to groups
* [BUGFIX] Minor changes and optimizations
* [NEW] Github deploy flow setup - https://github.com/WPGov/telegram-bot

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
