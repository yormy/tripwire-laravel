<?php

use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MissingModelConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MissingPageConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputsFilterConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilderWires;
use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Geo;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Lfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Php;
use Yormy\TripwireLaravel\Http\Middleware\Wires\RequestSize;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Rfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Session;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Swear;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\Regex;

/*
|--------------------------------------------------------------------------
| SWEAR words
|--------------------------------------------------------------------------
*/
$swearConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_SWEAR_ENABLED', env('TRIPWIRE_ENABLED', true)))
    //->trainingMode(false)
    //->methods(['post', 'put', 'patch', 'get'])
    ->attackScore(500)
    //->urls(UrlsConfig::make())
    //->inputFilter(InputsFilterConfig::make())
    //->tripwires(['blow'])
    //->punish(PunishConfig::make(10, 60 * 24, 5,))
    ->triggerResponse(
        BlockResponseConfig::make()
            ->json(JsonResponseConfig::make()->json([ 'data' => 'kkkkkk', 'err' =>'233']))
            ->html(HtmlResponseConfig::make()->exception(TripwireFailedException::class))
    );

/*
|--------------------------------------------------------------------------
| SQL Injection
|--------------------------------------------------------------------------
*/
$sqliConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_SQLI_ENABLED', env('TRIPWIRE_ENABLED', true)))
    //->trainingMode(false)
    //->methods(['post', 'put', 'patch', 'get'])
    ->attackScore(500)
    //->urls(UrlsConfig::make())
    //->inputFilter(InputsFilterConfig::make())
    ->tripwires([
        '#[\d\W](or 1=1|or 2=2|or\+1=1||or\+2=2)[\d\W]#iUu',
        '#[\d\W](union select|union join|union distinct)[\d\W]#iUu',
        '#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#iUu',
        '#(ROWNUM=ROWNUM)#iUu',
    ])
    //->punish(PunishConfig::make(10, 60 * 24, 5,))
    ->triggerResponse(
        BlockResponseConfig::make()
            ->json(JsonResponseConfig::make()->json([ 'data' => 'kkkkkk', 'err' =>'233']))
            ->html(HtmlResponseConfig::make()->exception(TripwireFailedException::class))
    );

/*
|--------------------------------------------------------------------------
| Local File Inclusion
|--------------------------------------------------------------------------
*/
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
    '…',  // some wierd ... converted into 1 dot
    'zip://',
    'php://',
    'file=expect:',
    'http:%252f%252',
    'data://text/plain;',
    'php:expect://',
]);

$lfiConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_LFI_ENABLED', env('TRIPWIRE_ENABLED', true)))
    //->trainingMode(false)
    //->methods(['post', 'put', 'patch', 'get'])
    //->attackScore(500)
    //->urls(UrlsConfig::make())
    //->inputFilter(InputsFilterConfig::make())
    ->tripwires([
        '#\.\/..\/#is',
        '#\.\.[\s]*/[\s]*\.#iUu', // ..[ ]*/[ ]*.
        $forbiddenTokens,
        $commonFilesString,
    ])
    //->punish(PunishConfig::make(10, 60 * 24, 5,))
//    ->triggerResponse(
//        BlockResponseConfig::make()
//            ->json(JsonResponseConfig::make()->json([ 'data' => 'kkkkkk', 'err' =>'233']))
//            ->html(HtmlResponseConfig::make()->exception(TripwireFailedException::class))
//    );
;

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
$evilStart = Regex::forbidden([
    '&lt;scrscriptipt',
    '%253c',
    '%252F',
    '\<script\>',
    '%3cscript%3e',
    '¼script¾',
    '%BCscript%BE',
    '&lt;script',
    '1script3',
    '1/script3',
    '<;/script>',
    '<;scrscriptipt>',
]);

$evilStartWithHashContent = Regex::forbidden([
    '&#97', //a
    'perl -e &apos;',
    'perl -e &#039;',
    'perl -e \'',
], '!');

$evilTokens = Regex::forbidden([
    'string.fromCharCode',
    'window.alert',
    '>><marquee',
    'dataformatas="html"',
    '\'%uff1cscript',
    '<scrscriptipt',
    '=expression\(',
        'onerror=\"javascript:document',
    '<;BR SIZE',
    '<br size',
    '&lt;br size',
    '<?xml version="1.0',
    'xss:e/\*\*/xpression',
    'document.vulnerable=',
    '&lt;/br',
    '\';\!--\"',
    ';\';;\!--"',
    '\&quot;',
    '=\\\";&;{',
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
    ->attackScore(0)
    ->tripwires([
        $evilStart,
        $evilTokens,
        "#&lt;(A|$orTags)( |&gt;|body)#iUu",  // &lt;???

        "#(<|&lt;);($orTags|\!--|\?|script|iframe|a href)#iUu",

        '#(<|&lt;)/(body|html)#iUu',

        '#&lt;(\!--|\? |div )#iUu',

        '!((java|live|vb)script|mocha|feed|data)(:|&colon;)(\w)*!iUu',
        '#-moz-binding[\x00-\x20]*:#u',

        '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',

        "#(\'|\\\");(.*);//#",  /* \";???;//*/

        $evilStartWithHashContent,

        '#(" onfocus=)#iUu',

        '#(\\[\\\\xc0]\[\\\\xBC])#iUu',

        "#</*($orTags)[^>]*>?#i",
        '#(onmouseover|onhover)[^>]*>?#i'
    ]);

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
    ]);

/*
|--------------------------------------------------------------------------
| GEO
|--------------------------------------------------------------------------
| ipapi, extremeiplookup, ipstack, ipdata, ipinfo, ipregistry
*/
$geoConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_GEO_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
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
    ->urls(UrlsConfig::make()->except(['api/v1/meber/*']))
    ->tripwires([
        'size' => 200    // max characters
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
        ])
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
        MissingModelConfig::make()->only([
            Tripwirelog::class,
            //'member.class'
        ])
    ]);

/*
|--------------------------------------------------------------------------
| Login Failed
|--------------------------------------------------------------------------
*/
$loginFailedConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_LOGINFAILED_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| Throttle Hit
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
    ->enabled(env('TRIPWIRE_BOT_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| REFERER
|--------------------------------------------------------------------------
*/
$refererConfig = WireDetailsConfig::make()
    ->enabled(env('TRIPWIRE_REFERER_ENABLED', env('TRIPWIRE_ENABLED', true)));

$res = ConfigBuilderWires::make()
    ->addWireDetails(Swear::NAME, $swearConfig)
    ->addWireDetails(Sqli::NAME, $sqliConfig)
    ->addWireDetails(Lfi::NAME, $lfiConfig)
    ->addWireDetails(Rfi::NAME, $rfiConfig)
    ->addWireDetails(Session::NAME, $sessionConfig)
    ->addWireDetails(Xss::NAME, $xssConfig)
    ->addWireDetails(Bot::NAME, $botConfig)
    ->addWireDetails(Php::NAME, $phpConfig)
    ->addWireDetails(Agent::NAME, $agentConfig)
    ->addWireDetails(Geo::NAME, $geoConfig)
    ->addWireDetails(Text::NAME, $textConfig)
    ->addWireDetails(RequestSize::NAME, $requestSizeConfig)
    ->addWireDetails('page404', $pageMissingConfig)
    ->addWireDetails('model404', $modelMissingConfig)
    ->addWireDetails('loginfailed', $loginFailedConfig)
    ->addWireDetails('throttle', $throttleHitConfig)
    ->addWireDetails('referer', $refererConfig)

    ->toArray();
return $res;
