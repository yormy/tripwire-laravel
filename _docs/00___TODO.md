# DOCS
explain where to place tripwire, if user block => tripwire na user known
* how to do encyption
* ->cookies('session_id') // ???? of hernoemen als fingerprinting
  setup payment method

## INTEGRATION TESTS
app-test : encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()


# Docs2 - Finalization
find todo
streamline docs with config file
install in clean laravel blade and record how to install and how to trigger
show video of reset




# PSALM
    <UndefinedThisPropertyFetch errorLevel="info" />
    <UndefinedThisPropertyAssignment errorLevel="info" />
    <UndefinedInterfaceMethod errorLevel="info" />
    <UnusedParam errorLevel="info" />
    <PossiblyNullReference errorLevel="info" />
    <PossiblyNullArgument errorLevel="info" />
    <PossiblyNullPropertyFetch errorLevel="info" />

    <UndefinedPropertyFetch errorLevel="info" />


# Release
* packagist submit
* tag version
* PINT
# Pipeline
styleci
travis ci
.. others ?



# -------------------------------------
#         WORK IN PROGRESS
# -------------------------------------

## How to summarize logs to a block / how to make teh block depended on the violations
Currently all violations scores are added to 1 large sum. This is then used to block or no block
Would be nice to block hardcore (sqli) or softcore (login) and show different views/messages/emails
How to spec what to add together to determine a block type

## Email Notifications
Send the user an email when they are blocked (only on first block of the day)

## File validation
- file checker : upload checker (laravelvalidation/upload middleware) +certain file types / sizes
  -- how to create unit test for this ?
- 
## RequestSize:
- add exclude fields
- specify per field ?
- add additional test for these specs

## Geofencing
- fix geo fencing, need working api key to ip lookup stuff
- use offline database https://www.maxmind.com/en/home
- when api calls, cache results to prevent  duplicate calls
- separate job to update location based on ip

# Management:
- response wrapper ?
- Admin sees blokcs, tripwire events, tarpits
- Admin overview of blocked users
- Mark block permanent / unmark
- get reset key
- Admin routes docs (registrations)

# Unit test
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
