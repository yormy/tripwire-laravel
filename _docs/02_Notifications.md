# Notifications
You can get notified whenever there is a block added. This notification can be by email or by slack. 
In the configuration you can specify the destinations. 
The destination can be either a single email/slack notification or you can send it to multiple destinations
Another option is that you listen to the ```TripwireBlockedEvent``` event and handle notifications yourself

## Email notifications

```
TRIPWIRE_NOTIFICATION_MAIL_ENABLED=true
TRIPWIRE_NOTIFICATION_MAIL_NAME=Tripwire
TRIPWIRE_NOTIFICATION_MAIL_FROM=tripwire@yourdomain.com
TRIPWIRE_NOTIFICATION_MAIL_TO=tripwire@yourdomain.com
```

If you want you can specify the mail template for the html mail and the plain text version
```
TRIPWIRE_NOTIFICATION_MAIL_PLAIN=tripwire-laravel::email_plain
TRIPWIRE_NOTIFICATION_MAIL_HTML=tripwire-laravel::email_html
```

## Slack notifications

### Create slack incoming webhook
- Open your slack application
- Click apps
- Find 'Incoming WebHooks' and add to slack
- Select channel
- Copy the slack-webhook and paste into your .env

### Setup env
```
TRIPWIRE_NOTIFICATION_SLACK_ENABLED=true
TRIPWIRE_NOTIFICATION_SLACK_FROM='Tripwire'
TRIPWIRE_NOTIFICATION_SLACK_CHANNEL=you-slack-webhook
TRIPWIRE_NOTIFICATION_SLACK_EMOJI=:japanese_goblin:```
```


