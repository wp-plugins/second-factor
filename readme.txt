=== Second Factor ===
Contributors: apokalyptik
Tags: authentication security email login notification factor
Requires at least: 3.0.1
Tested up to: 3.1
Stable tag: 1.0
Donate link: Patches Welcome
Require secondary authentication for registered user access
== Description ==
This plugin prevents logged in users from doing anything on your wordpress.org blog until they have verified their second factor of authentication.  The process goes like this:

1. A user logs into your blog.
  * Behind the scenes a bunch of cryptographic stuff happens and a key is generated and attached to that user. The key is overwritten with a new one every single time they log in. This key is emailed to that user (via the email address the user is registered under.)
1. The user gets the email with the code.
1. The user then enters the code at the page which is now presented to them when they are trying to access your blog
  * Behind the scenes the token is checked for validity, and a cookie is added to the users session.  They are now allowed access to your blog.  If the key changes (the user logs out, or is required to log in again) the cookie that they may have been using will no longer be valid and they will be asked to enter the new one that they get via email.
== Frequently Asked Questions == 
= Why? =
To add a second layer of security to your WordPress blog

= Why just email? =
If there's interest we can add sms, im, or other types of authentication

= Is the email/form configurable? =
No, if there's interest then we can work on that.

= What are users blocked from before authenticating? =
Everything.  If there's interest we can work on configurability

= Does this plugin affect anonymous users? =
No.

= Does this plugin affect commentors, or spam? =
No.
== Screenshots ==
1. Second Factor Authentication Page
== Changelog ==
= 1.0 =
Initial release
== Upgrade Notice ==
NA
== Installation ==
1. Make sure that you can get email from your blog, because upon installation you will be required to authenticate.  
1. You may have to log out and back in to have a token sent to you after installation.
