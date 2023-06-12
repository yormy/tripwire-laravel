<?php

use Mexion\BedrockUsers\Models\Member;
use Mexion\BedrockUsers\Models\Admin;
use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Exceptions\SwearFailedException;
use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Observers\Events\Failed\RequestChecksumFailedEvent;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\User;

return [
    /*
    |--------------------------------------------------------------------------
    | Enable the tripwire system
    |--------------------------------------------------------------------------
    | When disabled, nothing happens
    |
    */
    'enabled' => true,

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
    'training_mode' => false,

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    | This will log additional data to the database to diagnose false positives
    | or detailed data why a certain issue was triggered
    | fills fields: trigger_data and trigger_rule
    */
    'debug' => true,

    'datetime' => [
        'format' => 'Y-m-d H:m:s',
        'offset' => -2 * 60,   // Offset in minutes is you want to display in another timezone as your database. Ie.. database in UTC, Platform in New York -4
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'mail' => [
            'enabled' => env('FIREWALL_EMAIL_ENABLED', true),
            'name' => env('FIREWALL_EMAIL_NAME', 'Laravel Firewall'),
            'from' => env('FIREWALL_EMAIL_FROM', 'firewall@mydomain.com'),
            'to' => env('FIREWALL_EMAIL_TO', 'admin@mydomain.com'),
            'template' => env('FIREWALL_EMAIL_TO', 'tripwire-laravel::email'),
            'template_plain' => env('FIREWALL_EMAIL_TO', 'tripwire-laravel::email_plain'),
        ],

        'slack' => [
            'enabled' => env('FIREWALL_SLACK_ENABLED', true),
            'emoji' => env('FIREWALL_SLACK_EMOJI', ':japanese_goblin:'),
            'from' => env('FIREWALL_SLACK_FROM', 'Tripwire'),
            'to' => env('FIREWALL_SLACK_TO',''), // webhook url
            'channel' => env('FIREWALL_SLACK_CHANNEL', null), // set null to use the default channel of webhook
        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | Checksums
    |--------------------------------------------------------------------------
    | Some checksums are posted by the frontend and validated on the backend.
    | These header values of the config need to match with the settings of your frontend
    |
    */
    'checksums' => [
        'posted' => 'X-Checksum',   // need to match with your frontend
        'timestamp' => 'X-sand',    // need to match with your frontend
        'serverside_calculated' => 'x-checksum-serverside',
    ],

    'database_tables' => [
        'tripwire_logs' => 'tripwire_logs',
        'tripwire_blocks' => 'tripwire_blocks'
    ],

    'models' => [
        'log' => TripwireLog::class
    ],

    'cookie' => [
        'browser_fingerprint'=> 'session_id'
    ],

    'services' => [
        'request_source' => RequestSource::class,
        'user' => User::class,
        'ip_address' => IpAddress::class
    ],

    'log' => [
        'max_request_size' => 191,
        'max_header_size' => 191,
        'max_referer_size' => 191,
        'remove' => [
            'password'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Ignore
    |--------------------------------------------------------------------------
    | Globally ignore these input fields.
    | The values here are ignored regardless of the individual settings per checked.
    | If you want to ignore certain values only for a specific checker, specify it in there
    |
    */
    'ignore' => [
        'input' => [],      // i.e. locale, id,
        'cookie' => ['session_id'],
        'header' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Honeypots
    |--------------------------------------------------------------------------
    |
    |
    */
    'honeypots' => [
        'must_be_missing_or_false' => [
            'isAdmin',
            'debug',
            'logged_in',
            'is_admin',
            'is_debug',
            'show_log',
            'skip_encryption',
        ]
    ],

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
    'urls' => [
        'except' => ['*/ffff/*','logout'], // i.e. 'admin/*', no leading / */member/dashboard/*
    ],


    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    | This is a function to allow researcher and developers to clean the logs / blocks
    | so that they are not constantly blocked and cannot continue
    |
    */
    'reset' => [
        'allowed' => env('TRIPWIRE_WHITELIST', true),
        'soft_delete' => true, // false = delete the log/block records from the database,
        'link_expiry_minutes' => 30,
    ],


    /*
    |--------------------------------------------------------------------------
    | Whitelist
    |--------------------------------------------------------------------------
    | These ips will not be checked
    | When empty all ips will be checked
    |
    */
    'whitelist' => [
        'ips' => explode(',', env('TRIPWIRE_WHITELIST', '')),
    ],

    'block_code' => env('FIREWALL_BLOCK_CODE', 406),

    /*
    |--------------------------------------------------------------------------
    | Block Response
    |--------------------------------------------------------------------------
    | Specify how the system should handle a blocking request
    |
    */
    'block_response' => [
        'json' => [
            'code' => 506,
            'abort' => env('FIREWALL_BLOCK_ABORT', false), // true or false, or make this a code ? or message
        ],
        'html' => [
            'view' => 'tripwire-laravel::blocked'
        ],
    ],

    'checker_groups' => [
        'all' => [
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
            'tripwire.request_size'
        ],
        'server' => [
            'tripwire.agent',
            'tripwire.bot',
            'tripwire.geo',
            'tripwire.referer',
            'tripwire.session',
        ],
        'user' => [
            'tripwire.lfi',
            'tripwire.php',
            'tripwire.rfi',
            'tripwire.sqli',
            'tripwire.swear',
            'tripwire.text',
            'tripwire.xss',
        ],

        // you can create as many groups as you want
        //'custom' => [
        //    'tripwire.text',
        //    'tripwire.xss',
        //],
        // This can then be used as 'tripwire.custom' in your kernel
    ],
    /*
    |--------------------------------------------------------------------------
    | Trigger response
    |--------------------------------------------------------------------------
    | Specify how the default response should be if a tripwire is activated. This can be overwritten for specific tripwires
    |
    */

    /*
//'code' => 300,//env('FIREWALL_BLOCK_CODE'),
            'view' => env('FIREWALL_BLOCK_VIEW', null),
            //'redirectUrl' => env('FIREWALL_BLOCK_REDIRECT', null),
            'redirectUrl' => 'http://testapp.local/api/V1/member/account/profilenew',
            'abort' => env('FIREWALL_BLOCK_ABORT', false), // true or false
            //'exception' => new RequestChecksumFailedException(),
            'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
            'messageKey' => 'tripwide.blockie'
     */

    'punish' => [
        'score' => 800,
        'within_minutes' => 60 * 24,
        // note this will log increase on every violation that leads to a block
        // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
        'penalty_seconds' => 5
    ],

    'trigger_response' => [
        'json' => [
//            'code' => 409,
//            'abort' => true,// env('FIREWALL_BLOCK_ABORT', false),
            'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
        ],
        'html' => [
            'code' => 409,
           // 'exception' => new TripwireFailedException(),
//            'messageKey' => 'this is a message',
//            'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
        ],
    ],
];
