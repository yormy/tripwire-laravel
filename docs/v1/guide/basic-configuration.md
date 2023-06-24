# Configuration

You can get notified whenever there is a block added. This notification can be by email or by slack.
In the configuration you can specify the destinations.
The destination can be either a single email/slack notification or you can send it to multiple destinations
Another option is that you listen to the ```TripwireBlockedEvent``` event and handle notifications yourself

## Email notifications
```php
TRIPWIRE_NOTIFICATION_MAIL_ENABLED=true
TRIPWIRE_NOTIFICATION_MAIL_NAME=Tripwire
TRIPWIRE_NOTIFICATION_MAIL_FROM=tripwire@yourdomain.com
TRIPWIRE_NOTIFICATION_MAIL_TO=tripwire@yourdomain.com
```


## Slack notifications

### Create slack incoming webhook
- Open your slack application
- Click apps
- Find 'Incoming WebHooks' and add to slack
- Select channel
- Copy the slack-webhook and paste into your .env

### Setup env
```php
TRIPWIRE_NOTIFICATION_SLACK_ENABLED=true
TRIPWIRE_NOTIFICATION_SLACK_FROM='Tripwire'
TRIPWIRE_NOTIFICATION_SLACK_CHANNEL=you-slack-webhook
TRIPWIRE_NOTIFICATION_SLACK_EMOJI=:japanese_goblin:
```

## Ready & Test
Now you are all set to test and be amazed by the results

### step 1
Go to your app and in whatever field there is fill in the following
```php
<script alert('xss')>
```

This will result in a few actions
1) this will trigger the tripwire
2) returns to the user with a todo: default response?

### step 2
Now repeat this action x times (todo: how many)
And it will:
1) this will trigger the blocker
2) every request will be blocked
3) Just refresh your page (or go to any page on your site), and you will see that the user is blocked
