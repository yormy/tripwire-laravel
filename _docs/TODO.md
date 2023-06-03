# TODO
disable middleware on certain routes
Abstraction o/ or groups of checkers easy include in kernel
BOT checker is not on input level, but on all levels so should be logically in the first steps of the request.
LOG referer ?
training mode, log, but no blocks, or blocks are not activated => but blocks are recorded
Whitelist ip: do not check, or only those ips are allowed in the system

Moet ik violations bij elkaar optellen om te punishen (bv xss + sql wel), maar bv sql en swear niet.
Hoe spec ik dat dan in de config de score to trigger




Extend tripwires:
listen to login events and take actiosn
throttle events
other akunetic scanners
other priportal / bounty scanners
walk through akunetics
null byte checker
file checker
protect an url from being included

Whitelist ip when specced this ip will not be checked for rules. Idea: have whitelist for allowing entry of the system, managable by the database?
or do this with a perblock in the _blocks

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
