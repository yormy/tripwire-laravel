# Bot
:::tip Goal
Prevent certain bots
:::

## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Filters
Specify the list of bots that should be forbidden
https://github.com/JayBizzle/Crawler-Detect/blob/master/raw/Crawlers.txt

<!--@include: ./_filters.md-->

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(10)
    ->filters([
        'allow' => [], // ie Google Desktop
        'block' => [] // ie attohttpc
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
