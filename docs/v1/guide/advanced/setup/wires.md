# Wires
There are many different wires that can be applied as a singular, or applied as a group.
Either individually by class name, or as a group defined in your config

:::tip Order is important
The first tripped wire will stop the request, the rest will not be checked
:::

## Adding wires by name
```php
    use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;   // [!code focus]
    
    protected $middlewareGroups = [
        'web' => [
            ...
            Xss::class  // [!code focus]
            ...
        ],
```
[see more wires](../../references/wires.md)


## Adding as group of wires
```php
    protected $middlewareGroups = [
        'web' => [
            ...
            'tripwire.all' // [!code focus] // Uses all tripwires defined in the group 'all' in the config
        ],
```
See [wire groups](../configuration/wire-groups) on how to create new wire groups
