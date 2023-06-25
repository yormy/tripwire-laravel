# Specify how to respond to a malicious request

## HTML Specify the return view
When a HTML request is rejected then a page is displayed,

You can specify this view in your ```.env```
```php
TRIPWIRE_REJECT_PAGE='tripwire-laravel::blocked'
```

## JSON Specify the return data
You can specify the default return json, just encode it into a string into your ```.env```
```php
TRIPWIRE_REJECT_JSON='{"error":"seems to be a malicious request"}'
```

[more customization options](../../customization/reject.md)
