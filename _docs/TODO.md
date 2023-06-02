# TODO
disable middleware on certain routes
Abstraction o/ or groups of checkers easy include in kernel
BOT checker is not on input level, but on all levels so should be logically in the first steps of the request.
LOG referer ?
training mode, log, but no blocks, or blocks are not activated => but blocks are recorded
Whitelist ip: do not check, or only those ips are allowed in the system
Update system env variables for geo ip keys //env('IPSTACK_KEY') (ip lookup)

Extend tripwires:
listen to login events and take actiosn
throttle events
other akunetic scanners
other priportal / bounty scanners
walk through akunetics
null byte checker
file checker
protect an url from being included

# Management:
Way of reset for hackers, how
-Signed-dated url per user
-record resets
-how to generate / give out ?
$table->string('xid')->unique(); // customizable ? // still neeeded ?

field encryption

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
