# Options
This page will explain all the settings you can modify through **.env** variables. 
There are even more options to customize and you can find those in the customization sections.

## Enable or Disable (default enabled) 
```php
TRIPWIRE_ENABLED=false
```

## Training mode
[see training mode](training-mode.md)

## Debug mode
[see debug mode](debug-mode.md)

## Date settings
You can specify the date format to format the datetime of the blocked_until value.
The user is presented information when the block will end (```blocked_until```). 
This value can be formatted any way you want by 
* Specifying the format
* Specifying the offset for non UTC times, this is a minutes or plus in minutes

```php
TRIPWIRE_DATE_FORMAT='Y-m-d h:i:s'
TRIPWIRE_DATE_OFFSET=0
```

# Notifications

## Email notifications
You can specify the destination
and which templates (html and plain text) to use
```php
TRIPWIRE_NOTIFICATION_MAIL_ENABLED=true
TRIPWIRE_NOTIFICATION_MAIL_NAME=Tripwire
TRIPWIRE_NOTIFICATION_MAIL_FROM=tripwire@yourdomain.com
TRIPWIRE_NOTIFICATION_MAIL_TO=tripwire@yourdomain.com
TRIPWIRE_NOTIFICATION_MAIL_PLAIN=tripwire-laravel::email_plain
TRIPWIRE_NOTIFICATION_MAIL_HTML=tripwire-laravel::email_html
```

## Slack notifications
```php
TRIPWIRE_NOTIFICATION_SLACK_ENABLED=true
TRIPWIRE_NOTIFICATION_SLACK_FROM='Tripwire'
TRIPWIRE_NOTIFICATION_SLACK_CHANNEL=you-slack-webhook
TRIPWIRE_NOTIFICATION_SLACK_EMOJI=:japanese_goblin:
```
[create webhook in slack](../../references/slack-setup.md)

## Reset blocks
[remove blocks](../setup/reset.md)

## Whitelist Ips
You can whitelist certain IP's that will be excluded from checking their input.
Specify those IP's as a comma separated string;
```php
TRIPWIRE_WHITELIST_IPS=127.0.0.1,192.168.1.3
```

## Trigger Response
[see trigger response setup](reject-response.md)

## Block Response
[see block response setup](block-response.md)

