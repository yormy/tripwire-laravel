# Respond to blocked user/ip

Specify in your ``tripwire.php`` how tripwire should respond in case of the request came from a blocked user or ip

Create 2 objects to specify how to respond in case of a JSON and in case of an HTML request
```php
    ->blockResponse(
        JsonResponseConfig::make()->code(423),
        HtmlResponseConfig::make()->code(423)->view('tripwire-laravel::blocked'),
    )
```

See the options how to create these objects: 
* [JSON Response](json-response.md)
* [HTML Response](html-response.md)


hmm.. there are also env varialbe... advanced config vs customizing config ?
