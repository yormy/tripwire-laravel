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
Setup ```.env```
```php
TRIPWIRE_NOTIFICATION_SLACK_ENABLED=true
TRIPWIRE_NOTIFICATION_SLACK_FROM='Tripwire'
TRIPWIRE_NOTIFICATION_SLACK_CHANNEL=you-slack-webhook
TRIPWIRE_NOTIFICATION_SLACK_EMOJI=:japanese_goblin:
```
[create webhook in slack](../references/slack-setup.md)

## Ready & Test
Now you are all set to test and be amazed by the results

### step 1
Go to your app and in whatever field there is fill in the following
```php
<script alert('xss')>
```

This will:
1) Trigger the tripwire and record the violation
2) JSON requests return status 406 with data [ ]
3) HTML requests return with status 406 and a nasty block screen

### step 2
Now repeat this action 5 times

This will:
1) Trigger the blocker
2) Block every JSON with status code 423
3) Block every HTML request with status code 423 and show a view screen

:::info
All these reponses to requests and blocks are completely customizable
:::
