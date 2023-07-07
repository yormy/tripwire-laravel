# Browser Fingerprinting
:::tip Goal
To block a malicious request even if the hacker uses a different IP
:::

Browser fingerprinting is a technique to uniquely identify the browser of the client. This happens on the frontend by calculating a hash based on the type and settings of the browser being used.
This allows you to identify pretty uniquely what computer setup was used, and will be the same cross IP's used.

Tripwire accepts a browser fingerprint generated on the frontend for logging and blocking purposes

# Setup
Set the cookie that holds the browserfingerprint data (calculated and set by your frontend).
This is called ```session_id``` to mask it for hackers as being a fingerprinting. If you call it fingerprint, a hacker will get information on how your system works.
Although this is called ```session_id``` you can call it anything you like

```php
//tripwire
->browserFingerprint('session_id')
```

Just to be on the safe side, ignore the browserfingerprint cookie when running through tripwire
```php
->inputIgnore(InputIgnoreConfig::make()->cookies(['session_id']))
```

## Example frontend code to calculate and inject browser fingerprint
```js
// app.js
import {createBrowserFingerprint} from "./browserFingerprint";
createBrowserFingerprint();

//browserFingerprint.js
import FingerprintJS from "@fingerprintjs/fingerprintjs";
export function createBrowserFingerprint() {
    const fpPromise = FingerprintJS.load({monitoring: false});

    ;(async () => {
        const fp = await fpPromise
        const result = await fp.get()

        const visitorId = result.visitorId
        document.cookie = "session_id=" + visitorId;
    })();
}
```


session_id
