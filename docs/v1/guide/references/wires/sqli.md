# Sqli
:::tip Goal
Prevent sql injection
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of **regex** that should be forbidden
There are a few helper functions for you to build the regex, [how to build these regex](./regex.md)

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(500) // with the global punishScore of 1000, this will block the user/ip on the second attempt
    ->tripwires([
        "#[\d\W](union select|select *)[\d\W]#iUu", // just an example, the config contains many more
    ])
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
