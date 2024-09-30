=== NV Referral Code ===
Contributors: David FU
Tags: referral marketing, refer, referral, affiliate, affiliate marketing
Requires at least: 4.8
Tested up to: 6.5
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin brings referral marketing to your WordPress website. It's dead simple, fast, customizable, and it's all free!

== Description ==
NV Referral Code is a WordPress plugin that helps you generate a unique referral code for each of your users, enabling you to start your own version of referral marketing. Upon user registration, the plugin captures the refer code automatically. Additionally, NV Referral Code provides a user-friendly "copy refer link box" that allows users to easily copy and share their referral link.

This plugin is dependent only on the WordPress core and can be used with all other plugins. However, the logic after successful referral registration is up to you. To help with this, two important hooks are provided. For more information on how to use them, please refer to the plugin documentation, which can be found on the plugin website [Documentation](http://shalior.ir/wp-referral-code "Shalior").

Several shortcodes are available to help you place things where they need to be. A list of these shortcodes can be found in the plugin options.

All information related to the referral status of a user can be found on the user edit page (Dashboard->Users->select a user). Here, you can view how many users the user has invited and who they were referred by.

== Installation ==
note: on activation plugin will create refer codes for all of your users.

== Screenshots ==
1. Copy link shortcode example
2. Options page
3. List of shortcodes
4. Managing referrals in User edit page

== Changelog ==

= 1.5.0 =
add refer link for current web page.

= 1.4.10 =
* UI enhancements.

= 1.4.9 =
* Update styles to support more themes.

= 1.4.7 =
* Redesign copy link box, fix minor bugs.

= 1.4.6 =
* Minor fix to remove spaces around refer link in copy ref link box

= 1.4.5 =
* Fix: Make more statements translatable.
* Document: Add new shortcode to shortcodes list.

= 1.4.4 =
* Feature: New shortcode [wp-referral-code var="most_referring_users"] shows users with most referring

= 1.4.3 =
* Fixed: Prevent submitting himself and already referred user.

= 1.4.2 =
* Minor bug fix, correctly loading styles.

= 1.4.1 =
* Add support for PHP 8.*

= 1.4.0 =
* You can now manually add referral relation for users.
* Check user edit page (Wordpress admin -> users -> select a user -> scroll down) to see the new Add button.

= 1.3.2 =
* General fixes and refactors

= 1.3.1 =
* Fix a bug related to updating/setting custom refer codes

= 1.3.0 =
* new feature which allows setting custom refer code for users, See the new filed in edit user page

= 1.2.1 =
* show referral data in users table

= 1.2.0 =
* new feature to remove referral relation in user edit admin page

= 1.1.1 =
* update tested up to version: 5.7
* fix copy box shortcode not rendering in some page builders.

= 1.1.0 =
* New option to set ref code expiration time
* New shortcode to show invited users list in frontend. new hooks to customize list's look.
* New important filter to modify ref code on registration ('wp_referral_code_new_user_ref_code') which makes it possible to capture possible refer code value from registration form or other sources.
* minor bug fixes and performance improvements
* -Many thanks to Stefano (@sdotb) for testing and reviewing plugin

= 1.0.2 =
* this update ensures values in the invited users list are unique.

= 1.0.1 =
* This minor update includes code improvements and some new hooks to make the plugin easier to extend by developers. no breaking changes.
* add new filter 'wp_referral_code_validate_submission' to control wheter refer data should be submited to database.
* new helper function 'wp_referral_code_add_user_to_referrer_invite_list($user_id, $referrer_id)'

= 1.0.0 =
* First version
