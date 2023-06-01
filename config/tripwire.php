<?php

use Mexion\BedrockUsers\Models\Member;
use Mexion\BedrockUsers\Models\Admin;
use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Observers\Events\RequestChecksumFailedEvent;

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

    'whitelist' => explode(',', env('TRIPWIRE_WHITELIST', '')),

    'block_code' => env('FIREWALL_BLOCK_CODE', 406),

    'response' => [
        'block' => [
            //'code' => 300,//env('FIREWALL_BLOCK_CODE'),
            'view' => env('FIREWALL_BLOCK_VIEW', null),
            //'redirectUrl' => env('FIREWALL_BLOCK_REDIRECT', null),
            'redirectUrl' => 'http://testapp.local/api/V1/member/account/profilenew',
            'abort' => env('FIREWALL_BLOCK_ABORT', false),
            //'exception' => new RequestChecksumFailedException(),
            'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
            'messageKey' => 'tripwide.blockie'
        ],
    ],

    'middleware' => [
        'swear' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['post', 'put', 'patch'],

            'attack_score' => 55,

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

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],

            'punish' => [
                'score' => 80,
                'within_minutes' => 60 * 24,
                // note this will log increase on every violation that leads to a block
                // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
                'penalty_seconds' => '5'
            ],


            'response' => [
                'block' => [
                    //'messageKey' => 'ja.hallo',
                    'exception' => RequestChecksumFailedException::class,
                ],
            ],
        ],
    ],
];
