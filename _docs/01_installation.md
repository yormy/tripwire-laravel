# Optional
Publish view - todo
Publish translations - todo
Publish config - todo
Publish migrations - todo


# Installation tips
Although it works out of the box we suggest using it a while in training mode. This allows you to see what is going on without any request being blocked
If you turn on debug_mode then more data is recorded and after running it for a some time you can inspect the logs and blocks to see if there are any false positives
and modify the rules accordingly
If you notice false positives please let use know to so we can finetune the default config

```
TRIPWIRE_TRAINING_MODE=true
TRIPWIRE_DEBUG_MODE=true
```
