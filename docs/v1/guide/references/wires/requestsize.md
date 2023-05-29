# Request Size
:::tip Goal
Prevent extremely long request sizes that could indicate an malicious request
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the maximum size of fields in the request

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(10)
    ->tripwires([
        'size' => 200,    // max characters
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
