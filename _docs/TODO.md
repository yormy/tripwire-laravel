# TODO
- how to disable middleware on certain routes
- Abstraction o/ or groups of checkers easy include in kernel
- BOT checker is not on input level, but on all levels so should be logically in the first steps of the request., maybe name different ?


exception pass to tripwire to check models and throw events, eventueel dan in config welke afvangen en wat te doen

Moet ik violations bij elkaar optellen om te punishen (bv xss + sql wel), maar bv sql en swear niet.
Hoe spec ik dat dan in de config de score to trigger

- logout on certain actions/ blocks ?
- 
Specify type of block view/json:  violation is anders dan login throttle

simple text search ie nullbyte
how to spec models/pages not found 



nullbyte 
extend rules:
onmouseover
onhover
script
hacker polyglots and examples
role=admin
or 1=1
or+1=1 (+ is url encoded space)

anonymizer, extend model, prevent callable in other namespace? in base class/ trait anonymizabletrait

Extend tripwires:
listen to login events and take actiosn
throttle events
other akunetic scanners
other priportal / bounty scanners
walk through akunetics
null byte checker
file checker
extreme Large request (unless allowed)
certain file types / sizes
protect an url from being included

Whitelist ip when specced this ip will not be checked for rules. Idea: have whitelist for allowing entry of the system, managable by the database?
or do this with a perblock in the _blocks ie: persistent block

# Management:
Way of reset for hackers, how
-Signed-dated url per user
-record resets
-how to generate / give out ?
$table->string('xid')->unique(); // customizable ? // still neeeded ?

field encryption
fix geo fencing, need working api key to ip lookup stuff
Persistent block: do not delete / give warning to the admin before deletion, set to true when wanted

# Unit tests

# Documentation


# Unit test cases
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
