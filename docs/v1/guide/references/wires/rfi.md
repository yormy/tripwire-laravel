# Rfi
:::tip Goal
Prevent remote file inclusion
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of tokens that identify the remote file inclusion malicious request

:::warning
This will block all links in the request too, so users will not be able to enter any url
:::

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(500)
    ->tripwires([
        '#(http|ftp){1,1}(s){0,1}://.*#i',
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
