# Request Size
:::tip Goal
Prevent extremely long inputs in fields that could indicate an malicious request.
**Caution** Some fields (ie ```your bio```) could contain a long text.
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the maximum size of a field in the request.

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
