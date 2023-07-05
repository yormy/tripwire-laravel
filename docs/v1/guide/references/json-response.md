# JSON response object

The JSON response object specifies how tripwire will react in case of a json request. 
This object can be used for both responding after triggering a wire or when a user/ip is blocked.

You can use a fluent api to respond

## General
Simply abort the request with the specified code (406)
```php
JsonResponseConfig::make()->code(406)
```

## Exception
Throw an exception
**Note:** throwing an exception as a response is slightly slower than other types of responses
```php
JsonResponseConfig::make()->exception(TripwireFailedException::class)
```

## Message
Returns a translated message
```php
JsonResponseConfig::make()->messageKey('your-custom-translatable-message-key')
```

## Json
Returns json data, this can be anything you like.
You could also specify a redirection url here and have your frontend redirect to that url.
```php
JsonResponseConfig::make()->json([
    'error' =>'This request is not allowed'
    'action' => 'go hack somewhere else'
]),
```
