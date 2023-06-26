# Tripwire Regex
I build a few regex helper functions to make the config actually readable instead of 40 lines of regex. Long complicated regex also make mistakes really hard to debug. Hence a few helper functions

They can be found in ```Yormy\TripwireLaravel\Services\Regex```

# Constants

## Regex::FILLER
```php
    const FILLER = '[\s|\x00]'; // Different fillers to find filler stuffing
```

## Regex::FILLERSEMI
```php
    const FILLERSEMI = '[;|/]'; ; // Alternative fillers used in a different context
```

## Regex::QUOTE
```php
    const QUOTE = '["|\'|&quot;|&apos;]'; // Alternatives for quotes
```

## Regex::LT
```php
    const LT = '[<|&lt;|%3c|¼|%BC]';    // Alternatives used for <
```

## Regex::GT
```php
    const GT = '[>|%3e|¾|%BE]'; // Alternatives used for >
```

# Functions
## Regex::or()
This takes an array and returns a string where the items are concatinated with a | to include in a regex statement

```php
Regex::or([
    'aaa', 'bbb'
])
// results: 'aaa|bbb'
```

## Regex::forbidden()
This takes an array of words and coverts it to a regex string that can be directly included in the tripwires list

```php
$forbidden = Regex::forbidden([
    "aaa",
    "bbb",
]);

// results: '#(aaa|bbb)#iUu'
```

## Regex::makeWhitespaceSafe()
Takes a string with spaces and converts it to string with one or more spaces or fillers (like null byte).
This will prevent space/null byte stuffing

```php
Regex::or('hello world')
// results: 'hello[\s|\x00]+world'
// to find with 1 or more spaces/fillers between hello and world
```

## Regex::injectFillers()
This will take an array and covert it to an array whereby each element went through ```Regex::makeWhitespaceSafe```


## Example Sqli
```php
$q = REGEX::QUOTE;

$orStatements = Regex::or([
    "union select",
    "union \+ select",
    "{$q} or true",
]);

$orPostgressForbidden = Regex::forbidden([
    "pg_client_encoding",
    "get_current_ts_config",
]);

$sqliConfig = WireDetailsConfig::make()
    ->tripwires(
        regex::injectFillers([
            "#[\d\W]($orStatements)[\d\W]#iUu",
            $orPostgressForbidden,
        ])
    );
```

## Example Xss
```php
$lt = Regex::LT;
$gt = Regex::GT;
$f2 = Regex::FILLERSEMI;
$q = REGEX::QUOTE;


$evilStart = Regex::forbidden([
    "$lt script",
    "{$lt} {$f2} script",
]);

$evilTokens = Regex::forbidden([
    "{$gt} {$gt} {$lt} marquee",
    "onerror = $q javascript:document",
    "$lt $f2 BR SIZE",
    "style = $q",
    "= \\\" $f2 & $f2 {",
]);


$xssConfig = WireDetailsConfig::make()
    ->tripwires(
        Regex::injectFillers([
            $evilStart,
            $evilTokens,
        ])
    );
```
