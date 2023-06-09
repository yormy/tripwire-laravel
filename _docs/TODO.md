# TODO
- how to disable middleware on certain routes (ie large request)

Extend tripwires:
extreme Large request (unless allowed) -> how to disable ?

Alleen requestsize is aan, toch loopt die 4x door mijn except route isExcluded heen ?
TripwireBlockHandler called 3 times = 3x in kernel => combine ?


encryption is done in consumer derived model, which must somehow override findByIp (>where encrypted()




extend rules: hacker polyglots and examples

anonymizer, extend model, prevent callable in other namespace? in base class/ trait anonymizabletrait



Whitelist ip when specced this ip will not be checked for rules. Idea: have whitelist for allowing entry of the system, managable by the database?
or do this with a perblock in the _blocks ie: persistent block

# How to conclude a block / how to make teh block depended on the violations
Moet ik violations bij elkaar optellen om te punishen (bv xss + sql wel), maar bv sql en swear niet.
Hoe spec ik dat dan in de config de score to trigger
When I know how, I could spec the result
Specify type of block view/json:  violation is anders dan login throttle
- more details in the email ? (url / type ? (need loggable>block)), what triggered it ?

# Management:
Way of reset for hackers, how
-Signed-dated url per user
-record resets
-how to generate / give out ?
$table->string('xid')->unique(); // customizable ? // still neeeded ?

field encryption
fix geo fencing, need working api key to ip lookup stuff
Persistent block: do not delete / give warning to the admin before deletion, set to true when wanted
cleanup models

# Unit tests

# Documentation


# Unit test cases
import akunetics tests

hmm... have someone create multiple examples of triggers
SQLi:
```
(union select)
```

lfi
```
./
```

session:
```
:a:9:{
```

XSS
```
#-moz-binding:#u
```


# Extended features
Geofencing


PSALM
PINT


# EXTEND
file checker : upload checker (laravelvalidation/upload middleware) +certain file types / sizes
