# Referer
:::tip Goal
Prevent certain referrers
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Filters
Specify the list of referers that should be forbidden

<!--@include: ./_filters.md-->

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(1000)
    ->filters([
        'allow' => [],
        'block' => []
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
