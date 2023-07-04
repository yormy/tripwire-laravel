# Bot
:::tip Goal
Prevent certain bots
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Guards
Specify the list of bots that should be forbidden
https://github.com/JayBizzle/Crawler-Detect/blob/master/raw/Crawlers.txt

<!--@include: ./_guards.md-->

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(10)
    ->guards([
        'allow' => [], // ie Google Desktop
        'block' => [] // ie attohttpc
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
