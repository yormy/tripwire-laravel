## Methods
The methods specifies which methods should be inspected

Options:
* 'post'
* 'put'
* 'patch'
* 'get'
* 'all' or '*'

The 'all' or '*' is a alias to inspect all methods

```php
    ->methods(['post', 'put']) // only post and put method
```

```php
    ->methods(['*']) // all methods
```
