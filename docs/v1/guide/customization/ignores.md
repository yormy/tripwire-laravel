# Ignore inputs
You can exclude certain inputs from being protected

## Ignore cookies
This will ignore the cookie ```session_id```
```php
    ->inputIgnore(InputIgnoreConfig::make()
        ->cookies(['session_id'])
    )
```

## Ignore fields
Ignore the field ```first_name```
```php
    ->inputIgnore(InputIgnoreConfig::make()
        ->inputs(['first_name'])
    )
```

## Ignore header field
Ignore the header field ```x-attempt```
```php
    ->inputIgnore(InputIgnoreConfig::make()
        ->headers(['x-attempt'])
    )
```
