# Checksum
:::tip Goal
Prevent man-in-the-middle types of request changes.
:::

## Workings
In order to test vulnerabilities in a system a hacker typically creates a request by clicking on your site, and then intercepting that and changing it.
This changing can be detected if your frontend sends a checksum with the request to the backend which validates the checksum.
Tripwire handles the checksum validation and recording and blocking if the checksum fails (user modified their request).

**NOTE** Sure I can hear you think. But an attacker can change the checksum based on the changed request and then tripwire will not detect that.
Correct, but that is quite a hassle for every change in request parameters. 
Meaning that there is much much more effort needed to change a request than without this tripwire. We could go even fancier by encrypting the checksum. 
But that is on our roadmap

---todo----

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
