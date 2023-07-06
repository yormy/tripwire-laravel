
# Checksum - todo
:::tip Goal
Prevent man-in-the-middle types of request changes. By adding a checksum to the request and validate that in the backend
:::

## Workings
In order to test vulnerabilities in a system a hacker typically creates a request by clicking on your site, and then intercepting that and changing it.
This changing can be detected if your frontend sends a checksum with the request to the backend which validates the checksum.
Tripwire handles the checksum validation and recording and blocking if the checksum fails (user modified their request).

**NOTE** Sure I can hear you think. But an attacker can change the checksum based on the changed request and then tripwire will not detect that.
Correct, but that is quite a hassle for every change in request parameters. 
Meaning that there is much much more effort needed to change a request than without this tripwire. We could go even fancier by encrypting the checksum. 
But that is on our roadmap

## Installation
Add the ```ChecksumValidator``` as the first middleware in your set, before anything else. Other middleware could interact with the request (ie trimming) which would invalidate the checksum
```php
// kernel.php
class Kernel extends HttpKernel
{
    protected $middleware = [
        ChecksumValidator::class, //[!code focus] // need to be the very first in your middleware set !
        // ... 
```

## Setup
```php
// tripwire.php
    ->checksums(
        ChecksumsConfig::make()
            ->posted('X-Checksum') // the name of the field that includes your frontend calculated checksum
            ->timestamp('X-sand') // the name of the field that contains the frontend calculated timestamp
            ->serversideCalculated('x-checksum-serverside') // used internally to store the server side checksum for calculation purposes
    )
```
You can change the ```serversideCalculated('x-checksum-serverside')``` to whatever fieldname you want, as long as it does not conflicts with possible request fields


## Frontend setup with Axios

Adding a checksum is pretty easy with Axios interceptors

```js
import CryptoJS from 'crypto-js/crypto-js';

// Cleans the data for encoding purposes
function createChecksum(postedData) {
    if (postedData) {
        const data = JSON.stringify(postedData);

        return data.replace(/[^a-z0-9]/g, '');
    }

    return '';
}

// Hash the value
function hashValue(value: string) {
        return cryptojs.sha512(value);
}

const addChecksum = (authorizedConfig) => {
    authorizedConfig.headers['X-Checksum'] = hashValue(createChecksum(authorizedConfig.data));
}

axios.interceptors.request.use((config) => {
    const authorizedConfig = {...config};
    addChecksum(authorizedConfig);
    // ...

    return authorizedConfig;
});

```



### ---todo----


## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Guards
Specify the list of referers that should be forbidden

<!--@include: ./_guards.md-->

## Example
```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(1000)
    ->guards([
        'allow' => [],
        'block' => []
    ]);
    
    //... optional overrides        
````

[optional global overriders](./optionals.md)
