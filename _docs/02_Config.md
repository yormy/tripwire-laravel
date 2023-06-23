# Configuration
There are 2 config files. 
* Tripwire.php is the main config for the package
* Tripwire_wires.php is the config where all the wires are defined

Order of middleware is when it trips, the first tripped wire will explore, the rest will not be checked

Middleware groupings
Order of middleware is when it trips, the first tripped wire will explore, the rest will not be checked


TRIPWIRE_BLOCK_PAGE='tripwire-laravel::blocked'
TRIPWIRE_WHITELIST_IPS
