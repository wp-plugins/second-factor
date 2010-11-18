=== Second Factor ===
Contributors: apokalyptik
Tags: authentication security email login notification factor
Requires at least: 3.0.1
Tested up to: 3.1
Stable: 1.0
Require secondary authentication for registered user access
== Description ==
This plugin prevents logged in users from doing anything on your wordpress.org blog until they have verified their second factor of authentication.  The process goes like this:
<ul>
	<li>Step 1: A user logs into your blog.<br/>Behind the scenes a bunch of cryptographic stuff happens and a key is generated and attached to that user. The key is overwritten with a new one every single time they log in. This key is emailed to that user (via the email address the user is registered under.)</li>
	<li>Step 2: The user gets the email with the code.</li>
	<li>Step 3: The user then enters the code at the page which is now presented to them when they are trying to access your blog<br/>Behind the scenes the token is checked for validity, and a cookie is added to the users session.  They are now allowed access to your blog.  If the key changes (the user logs out, or is required to log in again) the cookie that they may have been using will no longer be valid and they will be asked to enter the new one that they get via email.</li>
</ul>
== Frequently Asked Questions == 
<p>Why?<br/>To add a second layer of security to your WordPress blog</p>
<p>Why just email?<br/>If there's interest we can add sms, im, or other types of authentication</p>
<p>Is the email/form configurable?<br/>No, if there's interest then we can work on that.</p>
<p>What are users blocked from before authenticating?<br/>Everything.  If there's interest we can work on configurability</p>
<p>Does this plugin affect anonymous users?<br/>No.</p>
<p>Does this plugin affect commentors, or spam?<br/>No.</p>
