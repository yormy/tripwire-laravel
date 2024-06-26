<?php

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
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;
use Yormy\TripwireLaravel\Services\User;
use Yormy\TripwireLaravel\Tests\Setup\Models\Admin;
use Yormy\TripwireLaravel\Tests\Setup\Models\Member;

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
    ->debugMode(env('TRIPWIRE_DEBUG_MODE', true))

    /*
    |--------------------------------------------------------------------------
    | Date formatting
    |--------------------------------------------------------------------------
    | Specify the format to show the date time to the users and the offset compared to UTC (in minutes)
    */
    ->dateFormat(
        env('TRIPWIRE_DATE_FORMAT', 'Y-m-d h:i:s'),
        env('TRIPWIRE_DATE_OFFSET', 0)
    )

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
            ->templateHtml(env('TRIPWIRE_NOTIFICATION_MAIL_HTML', 'tripwire-laravel::email')),
    ]
    )
    ->notificationSlack([
        NotificationSlackConfig::make(env('TRIPWIRE_NOTIFICATION_SLACK_ENABLED', false))
            ->from(env('TRIPWIRE_NOTIFICATION_SLACK_FROM', 'Tripwire'))
            ->channel(env('TRIPWIRE_NOTIFICATION_SLACK_CHANNEL', ''))
            ->emoji(env('TRIPWIRE_NOTIFICATION_SLACK_EMOJI', ':japanese_goblin:')),
    ]
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
    ->models(
        TripwireLog::class,
        TripwireBlock::class,
        Member::class,
        Admin::class,
    )

    ->browserFingerprint('session_id')

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */
    ->services(
        RequestSource::class,
        UserResolver::class,
        IpAddress::class
    )

    ->logging(
        LoggingConfig::make()
            ->remove([
                'password',
            ])
    )

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
            ->softDelete(env('TRIPWIRE_RESET_DELETE_SOFT', true))
            ->linkExpireMinutes(env('TRIPWIRE_RESET_LINK_EXPIRATION_MINUTES', 60 * 24 * 30))
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
    | Reject response - How to respond when a malicious request is detected
    |--------------------------------------------------------------------------
    */
    ->rejectResponse(
        JsonResponseConfig::make()->code(406)->json(json_decode(env('TRIPWIRE_REJECT_JSON', '[]'), true)),
        HtmlResponseConfig::make()->code(406)->view(env('TRIPWIRE_REJECT_PAGE', 'tripwire-laravel::blocked')),
    )

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

    /*
    |--------------------------------------------------------------------------
    | Blocking - How to respond to blocked requests
    |--------------------------------------------------------------------------
    | Specify how the system should handle a blocking request
    */
    ->blockCode(406)
    ->blockResponse(
        JsonResponseConfig::make()->code(423)->json(json_decode(env('TRIPWIRE_BLOCK_JSON', '[]'), true)),
        HtmlResponseConfig::make()->code(423)->view(env('TRIPWIRE_BLOCK_PAGE', 'tripwire-laravel::blocked')),
    )

    /*
    |--------------------------------------------------------------------------
    | Wire groups definitions
    |--------------------------------------------------------------------------
    */
    ->addWireGroup(
        'main',
        WireGroupConfig::make([
            'tripwire.honeypotwire',
            'tripwire.sqli',
            'tripwire.xss',
        ])
    )

    ->addWireGroup(
        'all',
        WireGroupConfig::make([
            'tripwire.honeypotwire',
            'tripwire.agent',
            'tripwire.bot',
            // 'tripwire.geo', // work in progress
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
    | - * /member/dashboard/*` (space between * and / here is needed as otherwise it is comment in comment
    |
    */
    ->urls(
        UrlsConfig::make()
            ->only([])
            ->except([
                '*/telescope/*',
            ])
    )

    ->toArray();

$res['user_fields'] = [
    'id' => 'xid',
    'foreign_key' => 'id',
    'firstname' => 'firstname',
    'lastname' => 'lastname',
    'email' => 'email',
];

return $res;
