# Honeypot
:::tip Goal
Lure attackers to fill in certain fields that only hackers can see or fill in.
:::

**Wikpedia:**
A honeypot is a computer security mechanism set to detect, deflect, or, in some manner, counteract attempts at unauthorized use of information systems. 
Generally, a honeypot consists of data that appears to be a legitimate part of the site which contains information or resources of value to attackers.
It is actually isolated, monitored, and capable of blocking or analyzing the attackers. 
This is similar to police sting operations, colloquially known as "baiting" a suspect


## Enabled
Enable or disable this wire

<!--@include: ./_methods.md-->

<!--@include: ./_attackscore.md-->

## Tripwires
Specify the list of **honeypots** that if these are filled in then you know this is a malicious request

## Example
The following example contains 2 honeypots ```debug``` and ```is_admin```.
These fields need to be absent in every request or null or 0.
Adding these fields to your request and setting it to null/0 lures a malicious person to change that into a 1 or true.

A normal user never sees that, so when Tripwire detects that these honeypots are filled with anything except null/0 then tripwire knows that this is a malicious request

```php
WireDetailsConfig::make()
    ->enabled(true)
    ->methods(['*'])
    ->attackScore(500)
    ->tripwires([
        'debug',
        'is_admin',
    ]);
    
    //... optional overrides        
````


[optional global overriders](./optionals.md)
