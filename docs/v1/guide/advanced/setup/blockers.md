# Blockers
:::info Blockers
Blockers are those elements that prevent any request to come through as long as a user/ip is block. Wires will not be checked as the user/ip is completely blocked from the application
:::

There are a few different types of blockers
* Based on a user on their userType and userId (```TripwireBlockHandlerUser```) this only works after a user has logged in
* Based on IP address (```TripwireBlockHandlerIp```)
* Based on a browserFingerprint (if your frontend supplies that) (```TripwireBlockHandlerBrowser```)
* General (```TripwireBlockHandlerAll```). This will perform as ```TripwireBlockHandlerIp```, ```TripwireBlockHandlerBrowser``` and ```TripwireBlockHandlerUser``` in one go


Adding blockers:
```php
    protected $middlewareGroups = [
        'web' => [
            ...
            TripwireBlockHandlerAll::class, // [!code focus] // Block on ip/browser or user
            ...
        ],

        'api' => [
            'auth:sanctum', // [!code focus] // or any other auth checker must be present for the block on user to work
            ...
            TripwireBlockHandlerUser::class // [!code focus] // must be added after a user has been authenticated and a userId is available
            ...
        ],
```

:::tip
Easiest is to always use ```TripwireBlockHandlerUser::class``` and place that after any authentication
:::


## Example Early block
An alternative early block setup is to first block if the ip address is not accepted,
and only then continue through the request cycle
and when there is a user, check again if the user is not blocked
```php
    protected $middlewareGroups = [
        'web' => [
            TripwireBlockHandlerIp::class, // [!code focus] // Block on ip
            ...
            TripwireBlockHandlerUser::class // [!code focus] // must be added after a user has been authenticated and a userId is available
            ...
        ],
```
