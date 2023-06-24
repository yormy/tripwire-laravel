Route::TripwireResetRoutes();   // needs to have guest access/ todo also ignored by firewall... how? does this work
Convert contfig to env and docu
Convert all :self =>: static
find todo

Slack is unclear: => test
to ?
channel?

test:
publishing with tag?

TripwireAdminRoutes : split in api // reset ?

Idea: hero image logo
Yormy - tripwire + graphic?


# Documentation
app-test : encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()
app-test - how to disable middleware on certain routes (ie large request)

anonymizer, extend model, prevent callable in other namespace? in base class/ trait anonymizabletrait

# How to summarize logs to a block / how to make teh block depended on the violations
Currently all violations scores are added to 1 large sum. This is then used to block or no block
Would be nice to block hardcore (sqli) or softcore (login) and show different views/messages/emails
How to spec what to add together to determine a block type

# PSALM
        <UndefinedThisPropertyFetch errorLevel="info" />
        <UndefinedThisPropertyAssignment errorLevel="info" />
        <UndefinedInterfaceMethod errorLevel="info" />
        <UnusedParam errorLevel="info" />
        <PossiblyNullReference errorLevel="info" />
        <PossiblyNullArgument errorLevel="info" />
        <PossiblyNullPropertyFetch errorLevel="info" />

        <UndefinedPropertyFetch errorLevel="info" />

PINT

# Documentation 
# EXTEND
Send the user an email when they are blocked (only on first block of the day)

## File validation
- file checker : upload checker (laravelvalidation/upload middleware) +certain file types / sizes
-- how to create unit test for this ?

## Geofencing
fix geo fencing, need working api key to ip lookup stuff

# Management:
response wrapper ?
Admin sees blokcs, tripwire events, tarpits
Admin overview of blocked users
Mark block permanent / unmark
get reset key

# Unit test
- globally ignore certain cookies
- globally ignore certain headers
- Agent
- Referer
- php
- swear
- rfi
- bot



Show docs:
Run npm run docs:preview from host
