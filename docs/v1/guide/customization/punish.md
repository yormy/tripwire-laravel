# Customizing the punish rules

    /*
    |--------------------------------------------------------------------------
    | Punish
    |--------------------------------------------------------------------------
    | punish at what score level
    | score reached within x minutes to punish, if score is reached over more time, no punishment
    | Penalty block for x seconds
    | note this will log increase on every violation that leads to a block
    | the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
    */
    ->punish(1000, 60 * 24, 5)


# TODO:
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */
    ->models(TripwireLog::class)
    ->cookies('session_id')


    ->logging(LoggingConfig::make()->remove(['password']))

    ->inputIgnore(InputIgnoreConfig::make()->cookies(['session_id']))


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


    /*
    |--------------------------------------------------------------------------
    | Blocking - How to respond to blocked requests
    |--------------------------------------------------------------------------
    | Specify how the system should handle a blocking request
    */
    ->blockCode(406)
