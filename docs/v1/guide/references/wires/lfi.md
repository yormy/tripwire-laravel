# Lfi
:::tip Goal
Prevent local file inclusion
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of tokens that identify the local file inclusion malicious request

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(500)
    ->tripwires([
        '#\.\/..\/#is',
    ])
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
