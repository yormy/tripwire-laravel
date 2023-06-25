# Respond to rejected request

Specify in your ``tripwire.php`` how tripwire should respond in case of the request is rejected

Create 2 objects to specify how to respond in case of a JSON and in case of an HTML request
```php
    ->rejectResponse(
        JsonResponseConfig::make()->code(423),
        HtmlResponseConfig::make()->code(423)->view('tripwire-laravel::blocked'),
    )
```

See the options how to create these objects:
* [JSON Response](json-response.md)
* [HTML Response](html-response.md)

