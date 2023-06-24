# Installation tips
You can simply install everything and use it right out of the box. This will add a tremendous layer of protection to your site and reduce the levels of malicious request ever coming through.

There might be requests that are generally considered malicious, but in your case they need to come through in certain areas of your app. This is not always clear when you first start using a firewall.

To help you fine tune your settings there is a training mode. While Tripwire is in 'training-mode', it will function as normal with recording of malicious requests and adding blocks and increasing block times. 
This is all just as in a normal mode. The only difference is that users/ip and requests are not actually blocked, but just continue to your app.
This way you can inspect the logs/blocks in your database to see if there are false positives recorded, and adjust your settings accordingly.
When you are satisfied and no false positives are recorded, you can remove the training mode again.

At the same time when enabling training mode we advise you to also enable 'debug_mode'. This will log some additional information to the database for inspection which wire was triggered and why. 
This greatly speeds up your investigation of false positives.

Update your ```.env```

``` php
TRIPWIRE_TRAINING_MODE=true
TRIPWIRE_DEBUG_MODE=true
```

## Feedback
If you notice false positives that I caused by my ruleset, please let me know and I will adjust the rules accordingly. 
When you do so, please include the actual payload that was triggering the wire while it should not.

