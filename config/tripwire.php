<?php

use Yormy\TripwireLaravel\DataObjects\Config\ChecksumsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputIgnoreConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\LoggingConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ResetConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireGroupConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\User;

$res = ConfigBuilder::make()

    /*
    |--------------------------------------------------------------------------
    | Enable the tripwire system
    |--------------------------------------------------------------------------
    | When disabled, nothing happens
    |
    */
    ->enabled(env('TRIPWIRE_ENABLED', true))

    /*
    |--------------------------------------------------------------------------
    | Training mode
    |--------------------------------------------------------------------------
    | When enabled here the global system is in a training mode.
    | Events are logged, database is updated, blocks are added to the database
    |
    | The only thing that will not happen is
    | - requests are not blocked
    | - the user will not be blocked
    |
    | In the database you can see what would have happend.
    */
    ->trainingMode(env('TRIPWIRE_TRAINING_MODE', false))


    /*
    |--------------------------------------------------------------------------
    | Debug mode
    |--------------------------------------------------------------------------
    | This will log additional data to the database to diagnose false positives
    | or detailed data why a certain issue was triggered
    | fills fields: trigger_data and trigger_rule
    */
    ->debugMode(env('TRIPWIRE_DEBUG_MODE', false))

    /*
    |--------------------------------------------------------------------------
    | Date formatting
    |--------------------------------------------------------------------------
    */
    ->dateFormat('Y-m-f', 0)

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    ->notificationMail([
        NotificationMailConfig::make(env('TRIPWIRE_NOTIFICATION_MAIL_ENABLED', true))
            ->name(env('TRIPWIRE_NOTIFICATION_MAIL_NAME', 'Tripwire'))
            ->from(env('TRIPWIRE_NOTIFICATION_MAIL_FROM', 'tripwirel@yourdomain.com'))
            ->to(env('TRIPWIRE_NOTIFICATION_MAIL_TO', 'tripwire@yourdomain.com'))
            ->templatePlain(env('TRIPWIRE_NOTIFICATION_MAIL_PLAIN', 'tripwire-laravel::email_plain'))
            ->templateHtml(env('TRIPWIRE_NOTIFICATION_MAIL_HTML', 'tripwire-laravel::email'))
            ]
    )
    ->notificationSlack([
        NotificationSlackConfig::make(env('TRIPWIRE_NOTIFICATION_SLACK_ENABLED', false))
            ->from(env('TRIPWIRE_NOTIFICATION_SLACK_FROM', 'Tripwire'))
            ->channel(env('TRIPWIRE_NOTIFICATION_SLACK_CHANNEL', ''))
            ->emoji(env('TRIPWIRE_NOTIFICATION_SLACK_EMOJI', ':japanese_goblin:'))
        ]
    )

    /*
    |--------------------------------------------------------------------------
    | Checksums
    |--------------------------------------------------------------------------
    | Some checksums are posted by the frontend and validated on the backend.
    | These header values of the config need to match with the settings of your frontend
    |
    */
    ->checksums(
        ChecksumsConfig::make()
            ->posted('X-Checksum')
            ->timestamp('X-sand')
            ->serversideCalculated('x-checksum-serverside')
    )

    /*
    |--------------------------------------------------------------------------
    | Database tables
    |--------------------------------------------------------------------------
    */
    ->databaseTables(
        'tripwire_logs',
        'tripwire_blocks'
    )

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    */
    ->models(TripwireLog::class)
    ->cookies('session_id')

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */
    ->services(
        RequestSource::class,
        User::class,
        IpAddress::class
    )

    ->logging(LoggingConfig::make()->remove(['password']))

    /*
    |--------------------------------------------------------------------------
    | Ignore
    |--------------------------------------------------------------------------
    | Globally ignore these input fields.
    | The values here are ignored regardless of the individual settings per checked.
    | If you want to ignore certain values only for a specific wire, specify it in there
    |
    */
    ->inputIgnore(InputIgnoreConfig::make()->cookies(['session_id']))

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    | This is a function to allow researcher and developers to clean the logs / blocks
    | so that they are not constantly blocked and cannot continue
    |
    */
    ->reset(
        ResetConfig::make(env('TRIPWIRE_RESET_ENABLED', true))
            ->softDelete(true)
            ->linkExpireMinutes(env('TRIPWIRE_RESET_LINK_EXPIRATION_MINUTES', 60*24*30))
    )

    /*
    |--------------------------------------------------------------------------
    | Whitelist
    |--------------------------------------------------------------------------
    | These ips will not be checked
    | When empty all ips will be checked
    |
    */
    ->whitelist(explode(',', env('TRIPWIRE_WHITELIST_IPS', '')))

    /*
    |--------------------------------------------------------------------------
    | Punish
    |--------------------------------------------------------------------------
    | punish at waht score level
    | score reached within x minutes to punish, if score is reached over more time, no punishment
    | Penalty block for x seconds
    | note this will log increase on every violation that leads to a block
    | the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
    */
    ->punish(800, 60 * 24, 5)

    /*
    |--------------------------------------------------------------------------
    | Blocking - How to respond to blocked requests
    |--------------------------------------------------------------------------
    | Specify how the system should handle a blocking request
    */
    ->blockCode(409)
    ->blockResponse(
        JsonResponseConfig::make()->code(506)->abort(env('TRIPWIRE_BLOCK_ABORT', false)),
        HtmlResponseConfig::make()->view(env('TRIPWIRE_BLOCK_PAGE', 'tripwire-laravel::blocked')),
    )

    /*
    |--------------------------------------------------------------------------
    | Trigger response - How to respond when a malicious request is detected
    |--------------------------------------------------------------------------
    */
    ->triggerResponse(
        JsonResponseConfig::make()->exception(TripwireFailedException::class),
        HtmlResponseConfig::make()->view(env('TRIPWIRE_BLOCK_PAGE', 'tripwire-laravel::blocked')),
    )

    /*
    |--------------------------------------------------------------------------
    | Wire groups definitions
    |--------------------------------------------------------------------------
    */
    ->addWireGroup(
        'all',
        WireGroupConfig::make([
            'tripwire.agent',
            'tripwire.bot',
            'tripwire.geo',
            'tripwire.lfi',
            'tripwire.php',
            'tripwire.referer',
            'tripwire.rfi',
            'tripwire.session',
            'tripwire.sqli',
            'tripwire.swear',
            'tripwire.text',
            'tripwire.xss',
            'tripwire.request_size',
            'tripwire.custom',
        ])
    )

    ->addWireGroup(
        'user',
        WireGroupConfig::make([
            'tripwire.lfi',
            'tripwire.php',
            'tripwire.rfi',
            'tripwire.sqli',
            'tripwire.swear',
            'tripwire.text',
            'tripwire.xss',
            'tripwire.custom',
        ])
    )

    ->addWireGroup(
        'server',
        WireGroupConfig::make([
            'tripwire.agent',
            'tripwire.bot',
            'tripwire.geo',
            'tripwire.referer',
            'tripwire.session',
        ])
    )

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


    ->toArray();

return $res;
