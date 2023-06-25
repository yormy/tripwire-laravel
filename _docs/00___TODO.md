Waar is de grens tussen dingen in env zetten en mensen config laten aanpassen !!!!

Installation:
composer & Datataba

Setup:
Changes to code

Basic Configuration
basic Setup env

Advanced Config
all env

Customization
publish config 
edit config


References
All wires





# CODE:
services needs interfaces (source/user/ip)
make blockmodel dynamic just like logmodel
check if loggingConfig does CHECK the data, but only not stores to databse.
rename trigger-response -> reject-response (this is more in line with 'blocked-response')
app-test : encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()
app-test - how to disable middleware on certain routes (ie large request)
Slack is unclear: => test \to ? channel?
Route::TripwireResetRoutes();   // needs to have guest access/ todo also ignored by firewall... how? does this work
Convert all :self =>: static
TripwireAdminRoutes : split in api // reset ?
??# Catch Model Binding???? => generic as in config, or specify in your model ?
When a hacker tries to change your routes and tries to access impproper missing models then you can catch this by
recode honeypot, that it is is just one of the many wires
remove xuid
generate reset url, how is this not blocked by tripwire ?

returning an exception is slower than an abort code
checksum validator-calculator in one ?
what does :redirect_url do in json response ?

Add to your model that you want to protect

datetime offset is in minutes, but variable is specified as just offset, whyy is datetime in 'Y-m-f' ?? 

```
use TripwireModelBindingTrait
```
Convert contfig to env and docu

# Pipeline
styleci
travis ci
.. others ?


# DOCS
streamline docs with config file
if methods is [] or missing, inspect all ?
readme cleanup
install in clean laravel blade and record how to install and how to trigger
show video of reset

find todo
create different use cases
Typical blade usage = view
Typical vue usage => json
Videos on installing and seeing it tripped
"Start page with : waht this page will answer?"
Config: if you set this to X, you can expect Y as end result



test:
publishing with tag?

Idea: hero image logo
Yormy - tripwire + graphic?


# Documentation

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
