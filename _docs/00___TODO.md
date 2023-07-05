Waar is de grens tussen dingen in env zetten en mensen config laten aanpassen !!!!

# CODE:

generate reset url, how is this not blocked by tripwire ?

# DOCS
| PAGE MISSING
| MODEL MISSING
| Login Failed
| Throttle Hit

packagist submit

refactor Guards => Filters oid
explain where to place tripwire, if user block => tripwire na user known

# Docs2 - Finalization
find todo
streamline docs with config file
install in clean laravel blade and record how to install and how to trigger
show video of reset

## INTEGRATION TESTS
app-test : encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()

Route::TripwireResetRoutes();   // needs to have guest access/ todo also ignored by firewall... how? does this work
checksum validator-calculator in one ?
what does :redirect_url do in json response ?


explain how to use checksumvalidation, early in request if request modding (like decoding),  or in tripwire, but calc up front

Docs of : Page Not found:
URLS && tripwire ??
->urls(UrlsConfig::make()->except(['api/v1/meber/*']))
->tripwires([
MissingPageConfig::make()->except([
'/membedie',
]),
]);



test:
publishing with tag?

- favicon ?


# Documentation

- h ow to do encyption
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */
    ->cookies('session_id') // ???? of hernoemen als fingerprinting

setup payment method

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

RequestSize:
- add exclude fields
- specify per field ?
- add additional test for these specs

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
- Bot
- Referer
- honeypot
- checksum?
- php
- swear
- rfi
- bot
- page404
- model404
- loginfailed
- throttletripped

unit test for block/allow


Show docs:
Run npm run docs:preview from host

# Pipeline
styleci
travis ci
.. others ?
