# Documentation
1 rename checkers to tripwires
2 refactor configs now we have objects

Done:
default
- ignore ip
- ignore input fiedl

todo
default
- ignore cookie
- ignore header


local ignores/ onlys

# Unit test cases
test ignore urls
test ignore ips
test training mode = no block, record
test honeypots
add model 404 filters filters

# TODO
- how to disable middleware on certain routes (ie large request)
- how to enable ray buggregator in testbench

## encryption
encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()


anonymizer, extend model, prevent callable in other namespace? in base class/ trait anonymizabletrait



Whitelist ip when specced this ip will not be checked for rules. Idea: have whitelist for allowing entry of the system, managable by the database?
or do this with a perblock in the _blocks ie: persistent block

# How to summarize logs to a block / how to make teh block depended on the violations
Moet ik violations bij elkaar optellen om te punishen (bv xss + sql wel), maar bv sql en swear niet.
Hoe spec ik dat dan in de config de score to trigger
When I know how, I could spec the result
Specify type of block view/json:  violation is anders dan login throttle
- more details in the email ? (url / type ? (need loggable>block)), what triggered it ?

# Extended features
Geofencing

PSALM
PINT


# EXTEND
file checker : upload checker (laravelvalidation/upload middleware) +certain file types / sizes
extend rules: hacker polyglots and examples

## controllers
### get log/block indexed search on ip:
/index?search=ip ?
### get log/block indexed search on userid-type:
/ index?userid=1&usertype='xxxxx'

## Geofencing
fix geo fencing, need working api key to ip lookup stuff

# Management:
response wrapper ?
