# Debug mode
The debug mode is intended to find why certain wires were tripped.
This will log some additional information to the database for inspection which wire was triggered and why.
This greatly speeds up your investigation of false positives.

Update your ```.env```

``` php
TRIPWIRE_DEBUG_MODE=true
```

## Feedback
If you notice false positives that I caused by my ruleset, please let me know and I will adjust the rules accordingly.
When you do so, please include the actual payload that was triggering the wire while it should not.

