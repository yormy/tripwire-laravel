# Notifications
Whenever you want to send a notification when a user/ip is blocked you can use the build in notification system and customize the email templates

## Email notifications template
If you want you can specify the mail template for the html mail and the plain text version
```
TRIPWIRE_NOTIFICATION_MAIL_PLAIN=tripwire-laravel::email_plain
TRIPWIRE_NOTIFICATION_MAIL_HTML=tripwire-laravel::email_html
```

## Events
You can also listen to the ```TripwireBlockedEvent``` event (or [other events](../references/events)) and handle all notifications yourself.
