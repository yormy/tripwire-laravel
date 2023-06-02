<?php

use Mexion\BedrockUsers\Models\Member;
use Mexion\BedrockUsers\Models\Admin;
use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Observers\Events\RequestChecksumFailedEvent;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\User;

return [
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

    'whitelist' => [
        'ips' => explode(',', env('TRIPWIRE_WHITELIST', '')),
        'routes' => [
            'except' => ['logout'], // i.e. 'admin/*', no leading /
        ],
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

    'trigger_response' => [
        'json' => [
            'abort' => 407,// env('FIREWALL_BLOCK_ABORT', false),
        ],
        'html' => [
            'exception' => new RequestChecksumFailedException(),
        ],
    ],

    'middleware' => [
        'swear' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['post', 'put', 'patch'],

            'attack_score' => 5,

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'inputs' => [
                'only' => [], // i.e. 'first_name'
                'except' => [], // i.e. 'password'
            ],

            'words' => [
                'joe',
                'no'
            ],

            'punish' => [
                'score' => 8000,
                'within_minutes' => 60 * 24,
                // note this will log increase on every violation that leads to a block
                // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
                'penalty_seconds' => 5
            ],


            'trigger_response' => [
                'json' => [
                    'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
                ],
                'html' => [
                    'exception' => new RequestChecksumFailedException(),
                ],
            ],
        ],
    ],
];
