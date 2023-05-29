# Request Validator


```php
    ChecksumCalculate::class,   // Calculates the checksum of the request, needs to be the very first in your middleware set before modifying any request item
    ...
    ChecksumValidateWire::class, // Trips when the calculated checksum does not match the frontend posted value (if provided)
```

    ->checksums(
        ChecksumsConfig::make()
            ->posted('X-Checksum')
            ->timestamp('X-sand')
            ->serversideCalculated('x-checksum-serverside')
    )
