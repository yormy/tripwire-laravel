Waar is de grens tussen dingen in env zetten en mensen config laten aanpassen !!!!

# CODE:
refactor Guards => Filters oid

## INTEGRATION TESTS
app-test : encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()

### Route::TripwireResetRoutes();   // needs to have guest access/ todo also ignored by firewall... how? does this work
-- generated reset url, how is this not blocked by tripwire ? + in docs

### checksum validator-calculator in one ?
todo
- add log event / blocking / response
- 
test 
- attackscore
- block auto
- response


### what does :redirect_url do in json response ?

### Docs of : Page Not found:
URLS && tripwire ??
->urls(UrlsConfig::make()->except(['api/v1/meber/*']))
->tripwires([
MissingPageConfig::make()->except([
'/membedie',
]),
]);

# DOCS
explain where to place tripwire, if user block => tripwire na user known
* how to do encyption
* ->cookies('session_id') // ???? of hernoemen als fingerprinting
  setup payment method


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

# Management:
- response wrapper ?
- Admin sees blokcs, tripwire events, tarpits
- Admin overview of blocked users
- Mark block permanent / unmark
- get reset key

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
