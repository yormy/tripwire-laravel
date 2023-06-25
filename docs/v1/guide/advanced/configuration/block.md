# Specify how to respond to a blocked request

## HTML Specify the return view
When a HTML request is blocked then a page is displayed,

You can specify this view in your ```.env```
```php
TRIPWIRE_BLOCK_PAGE='tripwire-laravel::blocked'
```

## JSON Specify the return data
You can specify the default return json, just encode it into a string into your ```.env```
```php
TRIPWIRE_BLOCK_JSON='{"error":"seems to be a malicious request"}'
```

[more customization options](../../customization/block.md)
