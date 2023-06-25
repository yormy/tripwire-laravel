# Configuration

You can get notified whenever there is a block added. This notification can be by email and/or by slack.

## Email notifications
```php
TRIPWIRE_NOTIFICATION_MAIL_ENABLED=true
TRIPWIRE_NOTIFICATION_MAIL_NAME=Tripwire
TRIPWIRE_NOTIFICATION_MAIL_FROM=tripwire@yourdomain.com
TRIPWIRE_NOTIFICATION_MAIL_TO=tripwire@yourdomain.com
```
If you want to use a different email template for your notifications you can set that up too. 
Look at [customization of notifications](../customization/notifications.md)

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
1) This will trigger the tripwire
2) Returns json requests with: [] and status 406
3) Returns html requests with a nasty block screen

### step 2
Now repeat this action 5 times (todo: how many)
And it will:
1) This will trigger the blocker
2) Every JSON request will be blocked with status code 423
3) Every HTML request will be blocked with status code 42 and show a view screen
