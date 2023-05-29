# Logging

## Remove sensitive data
Much information is logged in the database for analysis, blocking and forensics.
Some details you might not want to store as they contain private information (email, password, bank information, address).

One way is to encrypt the fields of the logged table ([see encryption](encryption)). The other way is to remove those fields completely from the logger
This can be done by specifying the fields that need to be removed


```php
    ->logging(LoggingConfig::make()->remove(['password','bank_account']))
```

## Limit size
User input can be so large that it is larger than your database can handle. You can specify the length of what is logged

```php
    ->maxRequestSize(200)
    ->maxHeaderSize(200)
    ->maxRefererSize(200)
```
