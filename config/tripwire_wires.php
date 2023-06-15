<?php

use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputsFilterConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilderWires;
use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Exceptions\SwearFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Swear;


$swearConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_SWEAR_ENABLED', env('TRIPWIRE_ENABLED', true)))
    //->trainingMode(false)
    //->methods(['post', 'put', 'patch', 'get'])
    //->attackScore(500)
    //->urls(UrlsConfig::make())
    //->inputFilter(InputsFilterConfig::make())
    ->tripwires(['blow'])
    //->punish(PunishConfig::make(10, 60 * 24, 5,))
//    ->triggerResponse(
//        BlockResponseConfig::make()
//            ->json(JsonResponseConfig::make()->json([ 'data' => 'kkkkkk', 'err' =>'233']))
//            ->html(HtmlResponseConfig::make()->exception(SwearFailedException::class))
//    )
;


$res = ConfigBuilderWires::make()
    ->addCheckerDetails(Swear::NAME, $swearConfig)

    ->toArray();
dd($res);

return [

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

        'tripwires' => [
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

        'tripwires' => [
            '#[\d\W](or 1=1|or 2=2|or\+1=1||or\+2=2)[\d\W]#is',
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

        'tripwires' => [
            '#\.\/..\/#is',
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

        'tripwires' => [
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

        'tripwires' => [
            // javascript:, livescript:, vbscript:, mocha: protocols
            '!((java|live|vb)script|mocha|feed|data):(\w)*!iUu',
            '#-moz-binding[\x00-\x20]*:#u',

            // Evil starting attributes
            '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',

            // Unneeded tags
            '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img)[^>]*>?#i',
            '#(onmouseover|onhover)[^>]*>?#i'
        ],

        'trigger_response' => [
            'json' => [
                'exception' => new SwearFailedException(),
            ],
            'html' => [
                'exception' => new SwearFailedException(),
            ],
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

        'tripwires' => [
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

        'tripwires' => [
            '#(http|ftp){1,1}(s){0,1}://.*#i',
        ],

        'guards' => [
            'allow' => [
                'https://allow-includes.com'
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
        'tripwires' => [
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

        'tripwires' => [
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

        'tripwires' => [
            'aaa',
            '\x00', //nullbyte
            // ...
        ],

        'trigger_response' => [
            'json' => [
//            'code' => 409,
//            'abort' => true,// env('FIREWALL_BLOCK_ABORT', false),
                'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
            ],
            'html' => [
                'code' => 409,
                //'exception' => TripwireFailedException::class,
                'messageKey' => 'this jouu dude',
                'redirect_url' => 'https://www.google.com'
//                'json' => [ 'data' => 'kkkkkk', 'err' =>'2'],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Missing
    |--------------------------------------------------------------------------
    */
    'model404' => [
        'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

        //    'training_mode' => false,    // this will override the global settings, if missing the global will be used

        'methods' => ['post', 'put', 'patch', 'get'],

        'attack_score' => 1,

        'tripwires' => [
            'Mexion\BedrockUsers\Models\Member',
            //'GFhVjBlVmkwUm14M'
        ],

        'punish' => [
            'score' => 10,
            'within_minutes' => 60 * 24,
            // note this will log increase on every violation that leads to a block
            // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
            'penalty_seconds' => 5
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Missing
    |--------------------------------------------------------------------------
    */
    'page404' => [
        'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

        //    'training_mode' => false,    // this will override the global settings, if missing the global will be used

        'methods' => ['post', 'put', 'patch', 'get'],

        'attack_score' => 1,

        'urls' => [
            'only' => [], // i.e. 'contact'
            'except' => ['member/*'], // i.e. 'admin/*'
        ],

        'tripwires' => [
            'Mexion\BedrockUsers\Models\Member', //?? only / except
            //'GFhVjBlVmkwUm14M'
        ],

        'punish' => [
            'score' => 10,
            'within_minutes' => 60 * 24,
            // note this will log increase on every violation that leads to a block
            // the first block will be for 5 seconds, de second for 25, the 3rd block is about 2 min, the 5th block is almost an hour
            'penalty_seconds' => 5
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Missing
    |--------------------------------------------------------------------------
    */
    'loginfailed' => [
        'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

        //    'training_mode' => false,    // this will override the global settings, if missing the global will be used

        'attack_score' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Throttle Hit
    |--------------------------------------------------------------------------
    */
    'throttle' => [
        'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

        //    'training_mode' => false,    // this will override the global settings, if missing the global will be used

        'attack_score' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Request size
    |--------------------------------------------------------------------------
    */
    'requestsize' => [
        'enabled' => env('FIREWALL_MIDDLEWARE_SWEAR_ENABLED', env('FIREWALL_ENABLED', true)),

        //    'training_mode' => false,    // this will override the global settings, if missing the global will be used

        'methods' => ['post', 'put', 'patch', 'get'],

        'attack_score' => 300,

        'urls' => [
            'only' => [], // i.e. 'contact'
            'except' => ['api/v1/meber/*'], // i.e. 'admin/*'
        ],

        'tripwires' => [
            'size' => 40    // max characters
            //'GFhVjBlVmkwUm14M'
        ],
    ],
];
