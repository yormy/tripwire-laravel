<?php

use Yormy\TripwireLaravel\DataObjects\Config\AllowBlockFilterConfig;
use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ChecksumsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MissingModelConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MissingPageConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilderWires;
use Yormy\TripwireLaravel\Exceptions\RequestChecksumFailedException;
use Yormy\TripwireLaravel\Http\Middleware\ChecksumValidateWire;
use Yormy\TripwireLaravel\Http\Middleware\Honeypot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Custom;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Geo;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Lfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Php;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Referer;
use Yormy\TripwireLaravel\Http\Middleware\Wires\RequestSize;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Rfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Session;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Swear;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\LoginFailedWireListener;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\PageNotFoundWireListener;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\RouteModelBindingWireListener;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\ThrottleHitWireListener;
use Yormy\TripwireLaravel\Services\IpLookup\ExtremeIplookup;
use Yormy\TripwireLaravel\Services\Regex;

/*
|--------------------------------------------------------------------------
| SWEAR words
|--------------------------------------------------------------------------
*/
$swearConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_SWEAR_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(10);

/*
|--------------------------------------------------------------------------
| SQL Injection
|--------------------------------------------------------------------------
*/
$q = REGEX::QUOTE;

$orStatements = Regex::or([
    'union select',
    "union \+ select",
    "select count \(",
    "select load_file \(",
    "version \(\)",
    'union join',
    'union distinct',
    "{$q} or true",
    "\)\) or true",
    "{$q} 1 = 1",
    "or \d* = \d*",
    "or \+\d* = \d*",
    'ROWNUM=ROWNUM',
    '@@connections',
    '@@version',
    '@@CPU_BUSY',
    'DBMS_PIPE.RECEIVE_MESSAGE',
    'SLEEPTIME',
    'drop table',
    'information_schema.tables',
    "or \( $q\w$q =",
    "or $q\w$q =",
]);

$orPostgressForbidden = Regex::forbidden([
    'pg_client_encoding',
    'get_current_ts_config',
    "quote_literal \(",
    "current_database \(",
]);

$orSqlLiteForbidden = Regex::forbidden([
    "sqlite_version \(",
    "last_insert_rowid \(",
    "last_insert_rowid \(",
]);

$sqliConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_SQLI_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(500)
    ->tripwires(
        regex::injectFillers([
            "#[\d\W]($orStatements)[\d\W]#iUu",
            "# sleep \( \d+ \) #iUu",
            "#\[\" .+ = .+ \"#iUu", //["1337=1337",
            "#BINARY_CHECKSUM \(.*\)#iUu",
            "#pow \(\d+#iUu",
            "#connection_id \(#iUu",
            "#crc32 \($q#iUu",
            "#USER_ID \( \d+ \) =#iUu",
            '#WAITFOR ( )DELAY#iUu',
            "#conv \($q#iUu",

            //oracle
            "#RAWTOHEX \($q#iUu",
            "#LNNVL \(\d+#iUu",
            "#BITAND \(\d+#iUu",

            //postgres
            $orPostgressForbidden,
            '#::(int|integer) = #iUu',

            //sqllite
            $orSqlLiteForbidden,
            "#= LIKE \($q#iUu",
        ]));

/*
|--------------------------------------------------------------------------
| Local File Inclusion
|--------------------------------------------------------------------------
*/
$f = REGEX::FILLER;

$commonFilesString = Regex::forbidden([
    '.ssh/id_rsa',
    '/access.log',
    '/error.log',
    '/htpasswd',
    '/.htpasswd',
    '/apache2.conf',
    'html/wp-config.php',
    '/www/configuration.php',
    '/inc/header.inc.php',
    '/default/settings.php',
    '/etc/issue',
    '/etc/passwd',
    '/etc/shadow',
    '/etc/group',
    '/etc/issue',
    '/etc/passwd',
    '/etc/shadow',
    '/etc/group',
    '/etc/hosts',
    '/etc/motd',
    '/etc/mysql/my.cnf',
    '/proc/self/environ',
    '/proc/version',
    '/proc/cmdline',
]);

$forbiddenTokens = Regex::forbidden([
    '%252e%252f',
    'zip://',
    'php://',
    'file=expect:',
    'http:%252f%252',
    'data://text/plain;',
    'php:expect://',
]);

$lfiConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_LFI_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->methods(['*'])
    ->attackScore(500)
    ->tripwires([
        '#\.\/..\/#is',
        "#\.\.$f/$f\.#iUu", // ..[ ]*/[ ]*.
        $forbiddenTokens,
        $commonFilesString,
    ]);

/*
|--------------------------------------------------------------------------
| RFI
|--------------------------------------------------------------------------
*/
$rfiConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_RFI_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        '#(http|ftp){1,1}(s){0,1}://.*#i',
    ]);

/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/
$sessionConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_SESSION_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        '@[\|:]O:\d{1,}:"[\w_][\w\d_]{0,}":\d{1,}:{@i',
        '@[\|:]a:\d{1,}:{@i',
    ]);

/*
|--------------------------------------------------------------------------
| XSS
| Help with understanding encoded attack vectors:
| https://dencode.com/en/
|--------------------------------------------------------------------------
*/
$lt = Regex::LT;
$gt = Regex::GT;
$f2 = Regex::FILLERSEMI;
$q = REGEX::QUOTE;

$evilStart = Regex::forbidden([
    "{$lt} scrscriptipt",
    '%253c',
    '%252F',
    "$lt script",
    '1script3',
    '1/script3',
    "{$lt} {$f2} script",
    "{$lt} {$f2} scrscriptipt",
    '\xc0',
    '\xBC',
]);

$evilStartWithHashContent = Regex::forbidden([
    '&#97', //a
    'perl -e &apos;',
    'perl -e &#039;',
    "perl -e $q",
], '!');

$evilTokens = Regex::forbidden([
    'string.fromCharCode',
    'window.alert',
    "{$gt} {$gt} {$lt} marquee",
    "dataformatas = {$q}html",
    "{$q}%uff1cscript",
    "= expression \(",
    "onerror = $q javascript:document",
    "$lt $f2 BR SIZE",
    "$lt br size",
    "{$lt}? xml version =\"",
    "xss : e/\*\*/xpression",
    'document.vulnerable =',
    "$lt $f2 BR",
    "{$q} $f2 \!--{$q}",
    ";{$q} $f2 $f2\!--{$q}",
    "style = $q",
    "\&quot;",
    "= \\\" $f2 & $f2 {",
]);

$orTags = Regex::or([
    'IMG',
    'layer',
    'object',
    'embed',
    'link',
    'meta',
    'xml',
    'html',
    'applet',
    'blink',
    'style',
    'script',
    'iframe',
    'frame',
    'frameset',
    'ilayer',
    'bgsound',
    'title',
    'base',
    'input',
    'body',
]);

$xssConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_XSS_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(250)
    ->tripwires(Regex::injectFillers([
        $evilStart,
        $evilTokens,
        "#&lt;(A|$orTags)( |&gt;|body)#iUu",

        "#$lt $f2 ($orTags|\!--|\?|script|iframe|a href)#iUu",

        "#$lt /(body|html)#iUu",

        //"#$lt (\!--|\? |div )#iUu",

        "!((java|live|vb)script|mocha|feed|data)(:|&colon;)(\w)*!iUu",
        '#-moz-binding[\x00-\x20]*:#u',

        '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',

        "#(\'|\\\");(.*);//#",  /* \";???;//*/

        $evilStartWithHashContent,

        "#( $q onfocus = )#iUu",

        "#(\\[\\\\xc0]\[\\\\xBC])#iUu",

        "#$lt /*($orTags)[^>]*>?#i",
        '#(onmouseover|onhover)[^>]*>?#i',
    ])
    );

/*
|--------------------------------------------------------------------------
| Custom
| A customizable wire
|--------------------------------------------------------------------------
*/
$customConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_CUSTOM_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(0)
    ->tripwires(Regex::injectFillers([
        '#example.malicious#iUu',
    ])
    );

/*
|--------------------------------------------------------------------------
| PHP
|--------------------------------------------------------------------------
*/
$phpConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_PHP_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
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
    ]);

/*
|--------------------------------------------------------------------------
| AGENT
| https://github.com/jenssegers/agent
|--------------------------------------------------------------------------
*/
$agentConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_AGENT_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        'browsers' => [
            'allow' => [],
            'block' => [], // i.e. 'IE', 'CHROME', 'FIREFOX'
        ],

        'platforms' => [
            'allow' => [],
            'block' => [], // i.e. 'OS X', 'UBUNTU', 'WINDOWS
        ],

        'devices' => [
            'allow' => [],
            'block' => [], // ie DESTOP, TABLET, MOBILE, PHONE
        ],

        'properties' => [
            'allow' => [], // i.e. 'Gecko', 'Version/5.1.7'
            'block' => [], // i.e. 'AppleWebKit'
        ],
    ]);

/*
|--------------------------------------------------------------------------
| GEO
|--------------------------------------------------------------------------
| ipapi, extremeiplookup, ipstack, ipdata, ipinfo, ipregistry
*/
$geoConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_GEO_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(1000)
    ->tripwires([
        'service' => ExtremeIplookup::class,
        'api_key' => '----',

        'continents' => [
            'allow' => ['*'], // i.e. 'Africa'
            'block' => ['Europe'], // i.e. 'Europe'
        ],

        'regions' => [
            'allow' => ['*'], // i.e. 'California'
            'block' => [], // i.e. 'Nevada'
        ],

        'countries' => [
            'allow' => ['*'], // i.e. 'Albania'
            'block' => [], // i.e. 'Madagascar'
        ],

        'cities' => [
            'allow' => ['*'], // i.e. 'Istanbul'
            'block' => [], // i.e. 'London'
        ],
    ]);

/*
|--------------------------------------------------------------------------
| TEXT
|--------------------------------------------------------------------------
| Plain text blacklist
| The difference between the text and word middleware is that words are separated by spaces,
| text can be anywhere in the content.
| ie xxxtestxxx
| text wire will tigger 'test' as it is in there
| word wire will not trigger as test is not a word (not surrounded by spaces)
*/
$textConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_TEXT_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        '\x00', //nullbyte
        // ...
    ]);

/*
|--------------------------------------------------------------------------
| Request size
|--------------------------------------------------------------------------
*/
$requestSizeConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_REQUESTSIZE_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        'size' => 200,    // max characters
    ]);

/*
|--------------------------------------------------------------------------
| PAGE MISSING
|--------------------------------------------------------------------------
*/
$pageMissingConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_PAGE404_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(10)
    ->urls(UrlsConfig::make()->except(['api/v1/meber/*']))
    ->tripwires([
        MissingPageConfig::make()->except([
            '/membedie',
        ]),
    ]);

/*
|--------------------------------------------------------------------------
| MODEL MISSING
|--------------------------------------------------------------------------
*/
$modelMissingConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_MODEL404_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(3)
    ->tripwires([
        MissingModelConfig::make()->except([
            Tripwirelog::class,
        ]),
    ]);

/*
|--------------------------------------------------------------------------
| HONEYPOTS
|--------------------------------------------------------------------------
*/
$honeypotConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_HONEYPOT_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(3)
    ->tripwires([
        'isAdmin',
        'debug',
        'logged_in',
        'is_admin',
        'is_debug',
        'show_log',
        'skip_encryption',
        'firstname'
    ]);

/*
|--------------------------------------------------------------------------
| LOGIN FAILED
|--------------------------------------------------------------------------
*/
$loginFailedConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_LOGINFAILED_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| TRHOTTLE HIT
|--------------------------------------------------------------------------
*/
$throttleHitConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_THROTTLEHIT_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| BOT
|--------------------------------------------------------------------------
| https://github.com/JayBizzle/Crawler-Detect/blob/master/raw/Crawlers.txt
*/
$botConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_BOT_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(1000)
    ->filters(AllowBlockFilterConfig::make()->allow(['aaaa'])->block([]));
/*
|--------------------------------------------------------------------------
| REFERER
|--------------------------------------------------------------------------
*/
$refererConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_REFERER_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(1000)
    ->filters(AllowBlockFilterConfig::make()->allow([])->block([]));

/*
|--------------------------------------------------------------------------
| CHECKSUM
|--------------------------------------------------------------------------
| Some checksums are posted by the frontend and validated on the backend.
| These header values of the config need to match with the settings of your frontend
|
*/
$checksumConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_CHECKSUM_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(10)
    ->methods(['*'])
    ->urls(
        UrlsConfig::make()
            ->except([
                'admin/*'
            ]
        )
    )
    ->config([
        'posted'=> 'X-Checksum', // the name of the field that includes your frontend calculated checksum
        'timestamp'=> 'X-sand', // the name of the field that includes your frontend calculated checksum
        'serverside_calculated'=> 'x-checksum-serverside', // the name of the field that includes your frontend calculated checksum
    ])
    ->rejectResponse(
        BlockResponseConfig::make(
            JsonResponseConfig::make()->code(406)->exception(RequestChecksumFailedException::class),
            HtmlResponseConfig::make()->code(406)->view(env('TRIPWIRE_REJECT_PAGE', 'tripwire-laravel::blocked')),
        )
    );

$res = ConfigBuilderWires::make()
    ->addWireDetails(Agent::NAME, $agentConfig)
    ->addWireDetails(Bot::NAME, $botConfig)
    ->addWireDetails(Geo::NAME, $geoConfig)
    ->addWireDetails(Lfi::NAME, $lfiConfig)
    ->addWireDetails(Php::NAME, $phpConfig)
    ->addWireDetails(Referer::NAME, $refererConfig)
    ->addWireDetails(RequestSize::NAME, $requestSizeConfig)
    ->addWireDetails(Rfi::NAME, $rfiConfig)
    ->addWireDetails(Session::NAME, $sessionConfig)
    ->addWireDetails(Sqli::NAME, $sqliConfig)
    ->addWireDetails(Swear::NAME, $swearConfig)
    ->addWireDetails(Text::NAME, $textConfig)
    ->addWireDetails(Xss::NAME, $xssConfig)
    ->addWireDetails(Custom::NAME, $customConfig)
    ->addWireDetails(Honeypot::NAME, $honeypotConfig)
    ->addWireDetails(PageNotFoundWireListener::NAME, $pageMissingConfig)
    ->addWireDetails(RouteModelBindingWireListener::NAME, $modelMissingConfig)
    ->addWireDetails(LoginFailedWireListener::NAME, $loginFailedConfig)
    ->addWireDetails(ThrottleHitWireListener::NAME, $throttleHitConfig)
    ->addWireDetails(Referer::NAME, $refererConfig)
    ->addWireDetails(ChecksumValidateWire::NAME, $checksumConfig)


    ->toArray();

return $res;
