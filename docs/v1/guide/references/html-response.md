# HTML response object

The HTML response object specifies how tripwire will react in case of a regular html request.
This object can be used for both responding after triggering a wire or when a user/ip is blocked.

You can use a fluent api to respond

## View
Show a blade view
```php
HtmlResponseConfig::make()->code(406)->view('tripwire-laravel::blocked'),
```

## Redirect
Redirect o another url
```php
HtmlResponseConfig::make()->redirect('https://go-away.com'),
```

## Exception
Throw an exception
**Note:** throwing an exception as a response is slightly slower than other types of responses
```php
HtmlResponseConfig::make()->exception(TripwireFailedException::class)
```

## Message
Returns a translated message
```php
HtmlResponseConfig::make()->messageKey('your-custom-translatable-message-key')
```

