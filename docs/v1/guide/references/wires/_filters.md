## Filters
This specifies what to allow and what to block

```php
    ->filters(AllowBlockFilterConfig::make()->allow(['allow-this'])->block(['block-this']));
```

### Example: Exception
allow must be at least ['*'] to allow all
```php
    ->allow([])->block([])
```

### Example: Allowed
```firefox``` is allowed
```php
    ->allow(['firefox'])->block([])
```

### Example: Blocked
```brave``` is blocked
```php
    ->allow(['*'])->block(['brave'])
```

### Example: Not Blocked
```firebrave``` is not blocked, so allowed
```php
    ->allow(['*'])->block([])
```

### Example: Not Blocked
```Chrome``` is not blocked, so allowed
```php
    ->allow(['*'])->block(['brave'])
```

### Example: Allowed and Blocked
```firebrave``` is both allowed and blocked, so allowed
```php
    ->allow(['firebrave'])->block(['firebrave'])
```

### Example: Unspecified
```firebrave``` is both not as allowed and not as blocked
The return depends on where it is used.

```php
    ->allow(['firebrave'])->block(['firebrave'])
```
