=== Telegram Bot & Channel ===
Contributors: Milmor
Version:	1.7
Stable tag:	trunk
Author:		Marco Milesi
Author URI:   https://profiles.wordpress.org/milmor/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F2JK36SCXKTE2
Tags: telegram, bot, newsletter, channel, group, automatic, stream
Requires at least: 3.8
Tested up to: 4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Complete plugin to create commands and build interactive bots for Telegram. Compatible with Zapier

== Description ==

Bots are simply Telegram accounts operated by software and they have AI features. With this plugin you can do anything: teach, play, search, broadcast, remind, connect, integrate with other services, or even pass commands to the Internet of Things.

This plugin allows you to create a Telegram Bot with your WordPress website and send content to your subscribers, groups or channel.

https://www.youtube.com/watch?v=jdQkFRYAoR0

> Please consider a [donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=F2JK36SCXKTE2) to make the plugin even better.

= Bot Features =
* Instant replies (on secure webhooks)
* **Custom keyboards**
* Instant send of new **posts/pages/post_types** with configurable template
* Supports chats, groups, supergroups and channels
* Create unlimited commands with replies (you can include other shortcodes)
* Create alias for commands
* View and manage your subscribers
* **Get and dynamically reply to users geolocation** with harvesine algorithm
* Send manual messages
* Create custom applicatons with **/$command $var1 $var2** format
* Add php to /$commands (requires [Insert Php](https://wordpress.org/plugins/insert-php
* **[Zapier](https://zapier.com)** integrated! You can create integrations with services you use!

= Channel Features =
* Stream your content to your Telegram channel

**Note:** your bot must be administrator of your channel for sending messages

**Warning:** due to Telegram limitation you need **SSL certificate** to manage a Telegram Bot. If you don't have one, register at [wptele.ga](https://wptele.ga) as a second step after installing the plugin. The service is free!

= Zapier = 
Zapier makes it easy to automate tasks between web apps. For example:

* send a news published on a website (based on RSS)
* send the weather to your subscribers, every day
* inform users when you upload an image on Instagram
* and much more… With 400+ Zapier Apps supported! More info on [wptele.ga/?p=442](https://wptele.ga/?p=442).

https://www.youtube.com/watch?v=14aEV0_FHFk

https://www.youtube.com/watch?v=frdub3fTdqk

= Examples =
* **[CosenzApp_bot](http://telegram.me/CosenzApp_bot)** (italian) - Guide for Cosenza city 

= Translations =
You can add your translations here: [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/telegram-bot) 

If you want to be translation editor for your locale, please send your username and language code (eg. it_IT) to milesimarco@outlook.com

= Support =
Give a look to our [faqs](https://wordpress.org/plugins/telegram-bot/faq/) or ask for [support](https://wordpress.org/support/plugin/telegram-bot). If you like this plugin, please leave a [review](https://wordpress.org/support/view/plugin-reviews/telegram-bot) or feedback so we can improve it!

== Installation ==
This section describes how to install the plugin and get it working.

1. Upload `telegram-bot` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Telegram settings page
4. Go through the steps and hit update!
5. If you don't have HTTPS, signup at [wptele.ga](https://wptele.ga) and register your website

== Frequently Asked Questions ==

= How do I create a bot? =
[wptele.ga/?p=101](https://wptele.ga/?p=101)

= How can i let users subscribe? =
[wptele.ga/?p=440](https://wptele.ga/?p=440)

= What is Zapier and how do i integrate it? =
[wptele.ga/?p=442](https://wptele.ga/?p=442)

= How to enable debug mode? =
If you are a developer, or just want a more complete "Telegram > Log" enable WP_DEBUG mode.
The plugin debug mode also allows to explore Telegram users and groups as standard posts. This let you to check custom fields for each users and modify them in real time. You'll notice a new column (= Telegram id for the user) in Subscribers and Groups page.
We don't suggest to keep WP_DEBUG if not for testing purposes.

= How to set up dynamic replies? =
The best way to integrate and add functions is to create another plugin.
For example, create a file called **telegram-bot-custom.php** and upload it to wp-content/plugins, then activate it.
Write the file as follows:

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
You can start from the previous custom plugin created, and add the following filter:

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

== Screenshots ==
1. plugin dashboard
2. subscribers list
3. commands list
4. command autoresponder
5. Zapier integration
6. plugin options
7. website registration on [wptele.ga](https://wptele.ga) (if you don't have SSL)
8. example from [CosenzApp_bot](http://telegram.me/CosenzApp_bot)
9. send to telegram function for all post types

== Changelog ==

= 1.7 11.09.2016 =
* Added php function to get and save incoming files. To be documented soon!
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
