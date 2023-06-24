# Wires
There are many different middleware wires that can be applied, or applied as a group.
There are many different wires that you can apply.
Either individually by class name, or as a group defined in your config
Order of middleware is when it trips, the first tripped wire will explore, the rest will not be checked
Adding wires:
```
    protected $middlewareGroups = [
        'web' => [
            ...
            'tripwire.all'  // Uses all tripwires defined in the group 'all' in the config
            Xss::class      // or just apply 1 wire
            ...
        ],

        'api' => [
            ...
            'tripwire.all' // Uses all tripwires defined in the group 'all' in the config
            ...
        ],
```
