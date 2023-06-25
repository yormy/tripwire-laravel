    /*
    |--------------------------------------------------------------------------
    | Urls
    |--------------------------------------------------------------------------
    | The urls to include and exclude
    | you can use the wildcard: *
    | urls should not start with a leading /
    | i.e.
    | - 'admin/*'
    | - * /member/dashboard/*` (space between * and / here is needed as othewise it is comment in comment
    |
    */
    ->urls(
        UrlsConfig::make()
            ->only([])
            ->except([])
    )
