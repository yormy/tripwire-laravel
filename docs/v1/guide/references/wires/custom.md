# Text
This is the place to start customizing or add rules that do not belong elsewhere

:::tip Goal
Easy to add new rules without having to touch the existing. Also a great place to test out new rules
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of words that should be forbidden

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->tripwires(Regex::injectFillers([
        "#example.malicious#iUu"
    ])
    );
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
