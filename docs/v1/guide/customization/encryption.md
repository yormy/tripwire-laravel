# Encryption of sensitive data

:::tip Goal
To ensure your database privacy settings you can encrypt values.
:::

Tripwire stores sensitive data as the IP address. You might want to encrypt that data. Tripwire itself does not include the encryption.
All (except ip, userId, userType and browserfingerprint) can be encrypted without any additional efforts as there are no searches on those fields.
* userId,userType and browserfingerprint are non privacy details, so they should not be encrypted to ease searching.
* IP address is privacy, and hence could be encrypted. If you do so you also need to make sure searching is still possible to block repeated offenders with the same ip.
For searching to be possible implement ```scopeByIp```


# Setup
Overwrite ```TripwireLog``` and ```TripwireBlock``` so that the encrypted values are...encrypted
Then overwrite the scopeByIp to search according to your encryption technology. In the following example I use ciphersweet to use encrypted search.

### Alternative approach
Encrypt however you like and include a hash of the ip. Then make sure the scopeByIp creates the hash from the paramater and searches the database

# Example with the use of CipherSweet
Add your new models to the config.
```php
// tripwire.php
    ->models(MyTripwireLog::class, MyTripwireBlock::class)
```

### Overwrite TripwireLog
```php
<?php

namespace App\Models;

use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use ParagonIE\CipherSweet\EncryptedRow;
use ParagonIE\CipherSweet\BlindIndex;

use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Yormy\TripwireLaravel\Models\TripwireLog as BaseTripwireLog;

class MyTripwireLog extends BaseTripwireLog implements CipherSweetEncrypted
{
    use UsesCipherSweet;

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('ip')
            ->addBlindIndex('ip', new BlindIndex('ip_index'));
    }

    public function scopeByIp($query, string $ipAddress)
    {
        return $query->whereBlind('ip', 'ip_index', $ipAddress);
    }
}
```

### Overwrite TripwireBlock
```php
<?php

namespace App\Models;

use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use ParagonIE\CipherSweet\EncryptedRow;
use ParagonIE\CipherSweet\BlindIndex;

use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Yormy\TripwireLaravel\Models\TripwireBlock as BaseTripwireBlock;

class MyTripwireBlock extends BaseTripwireBlock implements CipherSweetEncrypted
{
    use UsesCipherSweet;

    public static function configureCipherSweet(EncryptedRow $encryptedRow): void
    {
        $encryptedRow
            ->addField('blocked_ip')
            ->addBlindIndex('blocked_ip', new BlindIndex('blocked_ip_index'));
    }

    public function scopeByIp($query, string $ipAddress)
    {
        return $query->whereBlind('blocked_ip', 'blocked_ip_index', $ipAddress);
    }
}
```
