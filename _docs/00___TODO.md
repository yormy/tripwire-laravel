GEO:

todo: allow
'allow' => [],

'block' => ['CONTINENT'],



Waar is de grens tussen dingen in env zetten en mensen config laten aanpassen !!!!

# CODE:
pint: ????
bool                $enabled = true,
bool                $trainingMode = null,
array               $methods = ['*'],
int                 $attackScore = 0,
UrlsConfig          $urlsConfig = null,
InputsFilterConfig  $inputs = null,
array               $tripwires = [],

What is the difference between block/allow and onl/excpt
replace block/allow / only/except with services

guards 
- if the allow list is empty- ??
  if the block list is empty- ??


check if loggingConfig does CHECK the data, but only not stores to databse.
rename trigger-response -> reject-response (this is more in line with 'blocked-response')

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

regext, this replaces a space with an optional space, so unionselect is also recognized, this is not good I guess
    public static function makeWhitespaceSafe(string $signature): string
    {
        return str_replace(' ', self::FILLER. '*', $signature); // ????
    }


```
use TripwireModelBindingTrait
```
Convert contfig to env and docu

# DOCS
| PAGE MISSING
| MODEL MISSING
| Login Failed
| Throttle Hit

packagist submit

>>    ->addWireDetails('honeypots', $honeypotConfig) waarom hebben deze string names

refactor Guards => Filters oid
explain how to use checksumvalidation, early in request if request modding (like decoding),
or in tripwire, but calc up front
explain where to place tripwire, if user block => tripwire na user known


REWITE GEO
- losse services
- DTO
- stub to test / mock



streamline docs with config file

install in clean laravel blade and record how to install and how to trigger
show video of reset

find todo
create different use cases
Typical blade usage = view
Typical vue usage => json
Videos on installing and seeing it tripped
"Start page with : waht this page will answer?"
Config: if you set this to X, you can expect Y as end result


INTEGRATION TESTS
app-test : encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()
app-test - how to disable middleware on certain routes (ie large request)

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
