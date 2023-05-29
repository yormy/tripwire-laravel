# Urls to include or exclude
You can specify what urls to include or exclude from checking.
If you exclude an url than no wires will be used for that url.

### Default : Check all
Only is empty and except is empty.
When only is empty than all urls will be checked, and when except is empty none will be skipped
```php
    ->urls(
        UrlsConfig::make()
            ->only([])
            ->except([])
    )
```

# Only scan the members area and the api routes
The urls allow wildcarding, so the following setup checks all urls that start with
When specifying the urls, remember, they should not start with a /
* members/...
* api/...
```php
    ->urls(
        UrlsConfig::make()
            ->only([
                'members/*'
                'api/*'
            ])
    )
```

# Scan members area, except profile
Protect all urls in the members namespace, except for the ones in the member/profiles
```php
    ->urls(
        UrlsConfig::make()
            ->only([
                'members/*'
            ])
            ->except([
                'members/profile/*'
            ])  
    )
```
