# Text
:::tip Goal
Prevent certain text to be used
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
    ->attackScore(10)
    ->tripwires([
        '\x00', //nullbyte
        // ...
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
