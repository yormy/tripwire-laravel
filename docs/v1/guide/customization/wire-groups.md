# Wire groups
A wire group is a collection of wires that can be used as one in your middleware.
Default there are 3 wiregroups predefined.

To create a new wiregroup, all you need to do is to give it a ```name``` and a list of ```wires```

```php
    ->addWireGroup(
        'myname',  
        WireGroupConfig::make([
            'tripwire.agent',
            'tripwire.bot',
        ])
    )
```

Then you can use it like

```php
    'web' =>
        'tripwire.myname' //[!code focus]
    ],
```
