# Geo
:::warning Work in Progress
This is still work in process
This is a pretty slow wire, so if you do not need it, best is to keep it out of your set
:::

:::tip Goal
Prevent access from certain locations / geofencing
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
        'service' => 'ipstack',

        'continents' => [
            'allow' => [], // i.e. 'Africa'
            'block' => ['Europe'], // i.e. 'Europe'
        ],

        'regions' => [
            'allow' => [], // i.e. 'California'
            'block' => [], // i.e. 'Nevada'
        ],

        'countries' => [
            'allow' => [], // i.e. 'Albania'
            'block' => [], // i.e. 'Madagascar'
        ],

        'cities' => [
            'allow' => [], // i.e. 'Istanbul'
            'block' => [], // i.e. 'London'
        ],
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
