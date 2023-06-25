# Swear
:::tip Goal
Prevent swear words or other words in the requests
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
    ->methods(['post', 'put', 'patch', 'get'])
    ->attackScore(10)
    ->tripwires([
        'illegal-word-one',
        'illegal-word-two'
    ])
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
