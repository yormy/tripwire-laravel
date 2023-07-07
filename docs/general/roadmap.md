# Roadmap

## Pipeline upgrade
Add auto style checkers and integration checkers, which ones?
* travisci?
* styleci?
* laravel pint?
* psalm?


## Managment of blocks/events
### Frontend API:
- List of blocks
- List of events
- mark/unmark block permanent
- Documentation + registering routes
- Generic response wrapper 

### Frontend Views
- Show current blocks
- Show blocked users/ ips
- Show events
- mark/unmark block permanent
- get reset key
- Documentation

## Email Notifications
- Send the user an email when they are blocked (only on first block of the day)
- Maybe cache a value with a decay, that once released a new email can be sent ? Send 1 per day per Ip?, DDOS will send thousands of emails, Digest of blocked ip per day ?


## File Upload With Unit Tests
## How to determine if a file is an attempt of malicious request?
- type not equal to extension
- wrong extensions/ double extensions
- filename invalid / nullbyte
- Checkout my own upload checker (laravelvalidation/upload) to see possible malicious detection points
  
## RequestSize Config
- Allow fields to be excluded from the requestsize wire
- Specify size per field
- Add additional test for these specs

## GeoFencing and GeoData
* Test all integrations with lookup services
* Once IP is looked up, store in database so no subsequent calls needed (speed and costs reduction). Store IP  + data in separate table and just reference in log/block
* Collect location data from offline database https://www.maxmind.com/en/home
* Store locationdata in database, but use different async job to update the database with locationdata

## Unit Test
Creating more unit and feature tests like:

- globally ignore certain cookies
- globally ignore certain headers
- Agent
- Bot
- Referer
- honeypot
- php
- swear
- rfi
- bot
- throttletripped

## How to summarize logs to a block / how to make teh block depended on the violations
Currently all violations scores are added to 1 large sum. This is then used to block or no block
Would be nice to block hardcore (sqli) or softcore (login) and show different views/messages/emails
How to spec what to add together to determine a block type

## Review config comments
Are they still up to date with the docs

### PSALM Upgrade and Fix
    <UndefinedThisPropertyFetch errorLevel="info" />
    <UndefinedThisPropertyAssignment errorLevel="info" />
    <UndefinedInterfaceMethod errorLevel="info" />
    <UnusedParam errorLevel="info" />
    <PossiblyNullReference errorLevel="info" />
    <PossiblyNullArgument errorLevel="info" />
    <PossiblyNullPropertyFetch errorLevel="info" />
    
    <UndefinedPropertyFetch errorLevel="info" />
