## OnlyExcept
This specifies what to include and what to exclude

```php
        ->tripwires([
        MissingModelConfig::make()
            ->only([
                Tripwirelog::class,
            ]),
            ->except([
                MyModel::class
            ])
    ]);
```

If the value is not specified in the except section it is included

### Example: Missing Config
```chome``` missing config , so included

```php
    MissingPageConfig::make();
```

### Example: Empty Config
```chome``` missing config , so included

```php
    MissingPageConfig::make()
        ->only([]),
        ->except([])
```

### Example: As Only
```firefox``` as only , so included

```php
    MissingPageConfig::make()
        ->only(['firefox']),
        ->except([])
```

### Example: Not As Only = Included
```chrome``` as only , so included

```php
    MissingPageConfig::make()
        ->only(['firefox']),
        ->except([])
```

### Example: Not Except = Included
```chrome``` not except, so included
```php
    MissingPageConfig::make()
        ->only([]),
        ->except(['firefox'])
```

### Example: As Except = Excluded
```firefox``` as only and as except, so excluded
```php
    MissingPageConfig::make()
        ->only(['firefox']),
        ->except(['firefox'])
```

### Example: As Only And As Except = Excluded
```firefox``` as except, so excluded
```php
    MissingPageConfig::make()
        ->only([]),
        ->except(['firefox'])
```

### Example: Not Only = Excluded
```chome``` is not included, so it is excluded

```php
    MissingPageConfig::make()
        ->only(['falcon']),
        ->except([])

```
