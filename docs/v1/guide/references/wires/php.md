# Php
:::tip Goal
Prevent php injection
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of tokens that identify a php code attempt

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(500)
    ->tripwires([
        'glob://',
        'phar://',
        'php://',
    ])
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
