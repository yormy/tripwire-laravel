<?php

use Mexion\BedrockUsers\Models\Member;
use Mexion\BedrockUsers\Models\Admin;
use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Exceptions\SwearFailedException;
use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Observers\Events\RequestChecksumFailedEvent;
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
            'to' => env('FIREWALL_SLACK_TO','https://hooks.slack.com/services/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'), // webhook url
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
        ],
        'base' => [
            'tripwire.sqli',
            'tripwire.xss',
        ],
        'custom1' => [
            'tripwire.word',
            'tripwire.text',
        ],
        'custom2' => [
            'tripwire.rfi',
            'tripwire.lfi',
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

    'punish' => [
        'score' => 8000,
        'within_minutes' => 60 * 24,
        // note this will log increase on every violation that leads to a block
        // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
        'penalty_seconds' => 5
    ],

    'trigger_response' => [
        'json' => [
            'code' => 409,
            'abort' => true,// env('FIREWALL_BLOCK_ABORT', false),
        ],
        'html' => [
            'exception' => new TripwireFailedException(),
        ],
    ],

    'middleware' => [
        /*
        |--------------------------------------------------------------------------
        | SWEAR words
        |--------------------------------------------------------------------------
        */
        'swear' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

        //    'training_mode' => false,    // this will override the global settings, if missing the global will be used

            'methods' => ['post', 'put', 'patch', 'get'],

            'attack_score' => 500,

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'inputs' => [
                'only' => [], // i.e. 'first_name'
                'except' => [], // i.e. 'password'
            ],

            'words' => [
               'blow',
                //'GFhVjBlVmkwUm14M'
            ],

            'punish' => [
                'score' => 10,
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
                    'exception' => new SwearFailedException(),
                ],
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | SQL Injection
        |--------------------------------------------------------------------------
        */
        'sqli' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SQLI_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['put'],

            'attack_score' => 7,

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'inputs' => [
                'only' => [], // i.e. 'first_name'
                'except' => [], // i.e. 'password'
            ],

            'patterns' => [
                '#[\d\W](bounty)[\d\W]#is',
                '#[\d\W](union select|union join|union distinct)[\d\W]#is',
                '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is',
            ],

            'punish' => [
                'score' => 1999990,
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

        /*
        |--------------------------------------------------------------------------
        | Local File Inclusion
        |--------------------------------------------------------------------------
        */
        'lfi' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_LFI_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['get', 'delete', 'put'],

            'attack_score' => 5,

            'patterns' => [
                '#\.\/#is',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | SESSION
        |--------------------------------------------------------------------------
        */
        'session' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_SESSION_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['get', 'post', 'delete', 'put'],

            'attack_score' => 7,

            'patterns' => [
                '@[\|:]O:\d{1,}:"[\w_][\w\d_]{0,}":\d{1,}:{@i',
                '@[\|:]a:\d{1,}:{@i',
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | XSS
        |--------------------------------------------------------------------------
        */
        'xss' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_XSS_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['post', 'put', 'patch', 'put'],

            'attack_score' => 9,

            'patterns' => [
                // Evil starting attributes
                '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',

                // javascript:, livescript:, vbscript:, mocha: protocols
                '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
                '#-moz-binding[\x00-\x20]*:#u',

                // Unneeded tags
                '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img)[^>]*>?#i'
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | BOT
        |--------------------------------------------------------------------------
        */
        'bot' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_BOT_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['all'],

            'attack_score' => 9,

            // https://github.com/JayBizzle/Crawler-Detect/blob/master/raw/Crawlers.txt
            'guards' => [
                'allow' => ['s'], // i.e. 'GoogleSites', 'GuzzleHttp'
                'block' => [], // i.e. 'Holmes'
            ],

        ],

        /*
        |--------------------------------------------------------------------------
        | PHP
        |--------------------------------------------------------------------------
        */
        'php' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_PHP_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['get', 'post', 'delete','put'],

            'attack_score' => 9,

            'words' => [
                'bzip2://',
                'expect://',
                'glob://',
                'phar://',
                'php://',
                'ogg://',
                'rar://',
                'ssh2://',
                'zip://',
                'zlib://',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | RFI
        |--------------------------------------------------------------------------
        */
        'rfi' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_RFI_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['get', 'post', 'delete', 'put'],

            'attack_score' => 9,

            'patterns' => [
                '#(http|ftp){1,1}(s){0,1}://.*#i',
            ],

            'guards' => [
                'allow' => [
                    'https://example.com'
                ]
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | REFERER
        |--------------------------------------------------------------------------
        */
        'referer' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_REFERRER_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['all'],

            'attack_score' => 9,

            'guards' => [
                'allow' => [],
                'block' => ['s'],
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | AGENT
        |--------------------------------------------------------------------------
        */
        'agent' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_AGENT_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['all'],

            'attack_score' => 9,

            // https://github.com/jenssegers/agent
            'custom' => [
                'browsers' => [
                    'block' => [], // i.e. 'IE', 'CHROME', 'FIREFOX'
                ],

                'platforms' => [
                    'block' => [], // i.e. 'OS X', 'UBUNTU', 'WINDOWS
                ],

                'devices' => [
                    'block' => [], // ie DESTOP, TABLET, MOBILE, PHONE
                ],

                'properties' => [
                    'allow' => [], // i.e. 'Gecko', 'Version/5.1.7'
                    'block' => [], // i.e. 'AppleWebKit'
                ],
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | GEO
        |--------------------------------------------------------------------------
        */
        'geo' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_GEO_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['all'],

            'attack_score' => 9,

            'custom' => [
                // ipapi, extremeiplookup, ipstack, ipdata, ipinfo, ipregistry
                'service' => 'ipstack',

                'continents' => [
                    'allow' => [], // i.e. 'Africa'
                    'block' => ['Europe'], // i.e. 'Europe'
                ],

                'regions' => [
                    'allow' => [], // i.e. 'California'
                    'block' => [], // i.e. 'Nevada'
                ],

                'countries' => [
                    'allow' => [], // i.e. 'Albania'
                    'block' => [], // i.e. 'Madagascar'
                ],

                'cities' => [
                    'allow' => [], // i.e. 'Istanbul'
                    'block' => [], // i.e. 'London'
                ],

            ]

        ],

        /*
        |--------------------------------------------------------------------------
        | TEXT
        |--------------------------------------------------------------------------
        | Plain text blacklist
        | The difference between the text and word middleware is that words are separated by spaces,
        | text can be anywhere in the content.
        | ie xxxtestxxx
        | text checker will tigger 'test' as it is in there
        | word checker will not trigger as test is not a word (not surrounded by spaces)
        */
        'text' => [
            'enabled' => env('FIREWALL_MIDDLEWARE_GEO_ENABLED', env('FIREWALL_ENABLED', true)),

            'methods' => ['all'],

            'attack_score' => 1,

            'words' => [
                //'GFhVjBlVmkwUm14M'
            ],
        ],
    ],
];
