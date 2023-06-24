
## Blockers
There is a blocker that block a user on their userType and userId (TripwireBlockHandlerUser) this only works after a user has logged in
There is a blocker on IP address (TripwireBlockHandlerIp)
There is a blocker on a browserFingerprint (if your frontend supplies that) (TripwireBlockHandlerBrowser)
and a blocker that is a combination of user, ip and browser (TripwireBlockHandlerAll). This will perform as TripwireBlockHandlerIp, TripwireBlockHandlerBrowser and TripwireBlockHandlerUser in one go


Adding blockers:
```
    protected $middlewareGroups = [
        'web' => [
            ...
            TripwireBlockHandlerAll::class,
            ...
        ],

        'api' => [
            ...
            TripwireBlockHandlerUser::class // must be added after a user has been authenticated and a userId is available
            ...
        ],
```
