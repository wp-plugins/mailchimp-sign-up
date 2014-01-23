=== MailChimp Ajax Subscribe ===
Author: Jason Prescher
Contributors: jprescher
Donate link: http://jasonprescher.com
Tags: mailchimp, email, signup
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 0.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple Ajax MailChimp email submission form. 

== Description ==

Ajax MailChimp email submission. Does not support double opt-in or additional fields at this time. The goal was to create a simple email field to maximize email sign up submissions for your subscription database. These features may be include in later releases.

== Installation ==

1. Upload `mailchimp-signup` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure your settings using your MailChimp API key
4. Use the [mcsignup] shortcode in your page or see markup for template addition.

== Changelog ==

= 0.1.1 =
* Name Change and Bug Fixes

= 0.1 =
* beta release

== Markup Example ==

`<?php echo do_shortcode('[mcsignup]'); // MailChimp Sign Up Form  ?>`



 == Frequently Asked Questions == 
 

 == Upgrade Notice ==
Fixes result message not showing due to CSS positioning issues. 

 == Screenshots == 
1. Here's a screenshot of it in action
2. and another