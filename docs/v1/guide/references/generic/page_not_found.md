# Page Not Found
:::tip Goal
Normally a user clicks through your app from page to page and should never see a 404 Page Not found. 
Landing on a non-existing page could be an indication of someone prying on your system.
:::

## Additional Installation
:::tip Installation
The following wires need the [ExceptionInspector](../../advanced/setup/exceptions) to be setup
:::

In the config you can specify which pages are excluded or included




## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of agents that should be allowed or forbidden
https://github.com/jenssegers/agent

## Guards
<!--@include: ./_guards.md-->

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(10)
    ->tripwires([
        'browsers' => [
            'allow' => [],
            'block' => [], // i.e. 'IE', 'CHROME', 'FIREFOX'
        ],

        'platforms' => [
            'allow' => [],
            'block' => [], // i.e. 'OS X', 'UBUNTU', 'WINDOWS
        ],

        'devices' => [
            'allow' => [],
            'block' => [], // ie DESTOP, TABLET, MOBILE, PHONE
        ],

        'properties' => [
            'allow' => [], // i.e. 'Gecko', 'Version/5.1.7'
            'block' => [], // i.e. 'AppleWebKit'
        ],
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
