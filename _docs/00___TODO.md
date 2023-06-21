Unit tests ?
- resetroute missing if not set
- agent
- php
- swear
- rfi
- bot
- referer

Test that runs throught all wires with some approved/blocked sets, approved: lorum ipsum / faker sentence/ paragraph
Command to generate text in file. test loads that file 1 filetest per language

Quickly add cve protection ? => env ? config ? => special checker based on xss
- how to run test without clearing the database at the end by artisan command for customer
- custom quite dataprovider for testing all with data/ violoations all with data, to simply add new items


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

## File validation
- file checker : upload checker (laravelvalidation/upload middleware) +certain file types / sizes
-- how to create unit test for this ?

## Geofencing
fix geo fencing, need working api key to ip lookup stuff

# Management:
response wrapper ?

# Unit test
- globally ignore certain cookies
- globally ignore certain headers
- notifications to mail
- notifications to slack
