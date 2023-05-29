# Optional wire specification
Each wire can override the default settings like below. If they are not specified in a wire, the global settings are used

```php
WireDetailsConfig::make()
    // ...
    
    //... the following are all optional, if these are not present the global values are used        
    ->trainingMode(true) // set this to trainingMode
    ->debugMode(true) // set this to debugMode
    ->urls(UrlsConfig::make()) // urls to include or exclude
    ->inputFilter(InputsFilterConfig::make())
    ->punish(PunishConfig::make(1000, 60 * 24, 5))
    ->rejectResponse(
        JsonResponseConfig::make()->code(406)->json(json_decode(env('TRIPWIRE_REJECT_JSON', '[]'), true)),
        HtmlResponseConfig::make()->code(406)->view(env('TRIPWIRE_REJECT_PAGE', 'tripwire-laravel::blocked')),
    )
````
