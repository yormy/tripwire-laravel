# Training mode
There might be requests that are generally considered malicious, but in your case they need to come through in certain areas of your app.
This is not always clear when you first start using a firewall.

The training mode is designed to test your configuration rules over time to the type of requests you are receiving. 
Depending on your app you will receive different types of data in different areas of your app.
For example you do not want to allow a url in a username field (so include rfi-wire) but you do allow it in a github profile page fields (so exclude rfi-wire).

To help you fine tune your settings you can use training mode. While Tripwire is in 'training-mode', it will function as normal with recording of malicious requests and adding blocks and increasing block times.
This is all just as in a normal mode. The only difference is that users/ip and requests are not actually blocked, but just continue to your app.
This way you can inspect the logs/blocks in your database to see if there are false positives recorded, and adjust your settings accordingly.
When you are satisfied and no false positives are recorded, you can remove the training mode again.

Update your ```.env```

``` php
TRIPWIRE_TRAINING_MODE=true
```
