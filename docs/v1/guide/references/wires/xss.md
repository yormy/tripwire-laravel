# Xss
:::tip Goal
Prevent xss injection
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of **regex** that should be forbidden
There are a few helper functions for you to build the regex, [how to build these regex](./regex.md)

:::tip
The regex'es can become pretty complex here to catch many of the payloads. Use the [regex helpers](./regex.md) to keep your config clean and readable
:::

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(500)
    ->tripwires(
        Regex::injectFillers([
            "!((java|live|vb)script|mocha|feed|data)(:|&colon;)(\w)*!iUu",
        ])
    );
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
