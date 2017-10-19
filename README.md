# Subscribers Email Notification With Sendgrid
Send a email notification when publish a new post using sendgrid. This is a plugin thought and optimized for Wordpress VIP (It works too on normal Wordpress).  Tell me if you have any suggestion.

NOTES:
* You need to include jquery (To make this plugin, I use 3.2.1 version if you need a reference)
* The email is sent every 15 minute. You can modify that on wp-content/plugins/SubscribersEmailNotificationWithSendgrid/functions.php at line 101, changing the "interval" from 900 seconds to the expected seconds. (If you need to use this plugin on Wordpress VIP, the interval should be equals or greather than 900 seconds).
* The email template is just for demonstration. If you want to modify the email template, go to wp-content/plugins/SubscribersEmailNotificationWithSendgrid/emailtemplate.php and use the parameters
on that file.
* The email is sent only if 'post_status' == "publish",
* You can use this shortcode [SubscribersEmailNotificationForm]
* If you found any issue, please contact me to fix it.
* Any help is welcome.

PS: My english is not very good, sorry.
