# Notifications
You can get notified whenever there is a block added. This notification can be by email or by slack.
In the configuration you can specify the destinations.
The destination can be either a single email/slack notification or you can send it to multiple destinations
Another option is that you listen to the ```TripwireBlockedEvent``` event and handle notifications yourself

## Email notifications template
If you want you can specify the mail template for the html mail and the plain text version
```
TRIPWIRE_NOTIFICATION_MAIL_PLAIN=tripwire-laravel::email_plain
TRIPWIRE_NOTIFICATION_MAIL_HTML=tripwire-laravel::email_html
```

