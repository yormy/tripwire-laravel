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
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Geo;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Lfi;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Php;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\RequestSize;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Rfi;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Session;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Sqli;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Swear;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;
use Yormy\TripwireLaravel\Services\Regex;

/*
|--------------------------------------------------------------------------
| SWEAR words
|--------------------------------------------------------------------------
*/
$swearConfig = CheckerDetailsConfig::make()
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
            ->html(HtmlResponseConfig::make()->exception(SwearFailedException::class))
    );

/*
|--------------------------------------------------------------------------
| SQL Injection
|--------------------------------------------------------------------------
*/
$sqliConfig = CheckerDetailsConfig::make()
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
            ->html(HtmlResponseConfig::make()->exception(SwearFailedException::class))
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

$lfiConfig = CheckerDetailsConfig::make()
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
//            ->html(HtmlResponseConfig::make()->exception(SwearFailedException::class))
//    );
;

/*
|--------------------------------------------------------------------------
| RFI
|--------------------------------------------------------------------------
*/
$rfiConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_RFI_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        '#(http|ftp){1,1}(s){0,1}://.*#i',
    ]);

/*
|--------------------------------------------------------------------------
| SESSION
|--------------------------------------------------------------------------
*/
$sessionConfig = CheckerDetailsConfig::make()
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
]);

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
]);

$xssConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_XSS_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->attackScore(0)
    ->tripwires([
        $evilStart,
        $evilTokens,
        '#(\<script\>|%3cscript%3e|¼script¾|%BCscript%BE|&lt;script)#iUu',     // ??script?? variations
        '#&lt;(script|A|layer|object|embed|style|img|xss|link|meta|html|xml|body|iframe)( |&gt;|body)#iUu',  // &lt;???
        '#(<|&lt;);(IMG|layer|object|embed|link|meta|xml|html|\!--|\?|script|iframe|a href)#iUu',
        '#(<|&lt;)/(body|html)#iUu',
        '#(&lt;/br)#iUu',
        '#&lt;(\!--|\? |div )#iUu',

        // javascript:, livescript:, vbscript:, mocha: protocols
        '!((java|live|vb)script|mocha|feed|data)(:|&colon;)(\w)*!iUu',
        '#-moz-binding[\x00-\x20]*:#u',

        // Evil starting attributes
        '#(<[^>]+[\x00-\x20\"\'\/])(form|formaction|on\w*|style|xmlns|xlink:href)[^>]*>?#iUu',

        '(%253c|%252F)', // | /
        '(&#97)', // a
        "#(\'|\\\");(.*);//#",  /* \";???;//*/


        '!(perl -e &apos;|perl -e &#039;|perl -e \')!iUu',

        '#(" onfocus=)#iUu',
        '#(1script3|1/script3)#iUu',

        '#(\\[\\\\xc0]\[\\\\xBC])#iUu',
        '#(<;/script>|<;scrscriptipt>)#iUu',
        '#(=\\\";&;{)#iUu',

        '#(\';\!--\")#iUu',
        '#(;\';;\!--")#iUu',

        '#(\&quot;)#iUu',


        // Unneeded tags
        '#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base|img|input)[^>]*>?#i',
        '#(onmouseover|onhover)[^>]*>?#i'
    ]);

/*
|--------------------------------------------------------------------------
| PHP
|--------------------------------------------------------------------------
*/
$phpConfig = CheckerDetailsConfig::make()
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
$agentConfig = CheckerDetailsConfig::make()
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
$geoConfig = CheckerDetailsConfig::make()
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
| text checker will tigger 'test' as it is in there
| word checker will not trigger as test is not a word (not surrounded by spaces)
*/
$textConfig = CheckerDetailsConfig::make()
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
$requestSizeConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_REQUESTSIZE_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->urls(UrlsConfig::make()->except(['api/v1/meber/*']))
    ->tripwires([
        'size' => 200    // max characters
    ]);

/*
|--------------------------------------------------------------------------
| Page Missing
|--------------------------------------------------------------------------
*/
$pageMissingConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_PAGE404_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->urls(UrlsConfig::make()->except(['api/v1/meber/*']))
    ->tripwires([
        'Mexion\BedrockUsers\Models\Member', //?? only / except // this is s model not a page
    ]);

/*
|--------------------------------------------------------------------------
| MODEL MISSING
|--------------------------------------------------------------------------
*/
$modelMissingConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_MODEL404_ENABLED', env('TRIPWIRE_ENABLED', true)))
    ->tripwires([
        'Mexion\BedrockUsers\Models\Member',
        // except models ?
    ]);

/*
|--------------------------------------------------------------------------
| Login Failed
|--------------------------------------------------------------------------
*/
$loginFailedConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_LOGINFAILED_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| Throttle Hit
|--------------------------------------------------------------------------
*/
$throttleHitConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_THROTTLEHIT_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| BOT
|--------------------------------------------------------------------------
| https://github.com/JayBizzle/Crawler-Detect/blob/master/raw/Crawlers.txt
*/
$botConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_BOT_ENABLED', env('TRIPWIRE_ENABLED', true)));

/*
|--------------------------------------------------------------------------
| REFERER
|--------------------------------------------------------------------------
*/
$refererConfig = CheckerDetailsConfig::make()
    ->enabled(env('TRIPWIRE_REFERER_ENABLED', env('TRIPWIRE_ENABLED', true)));

$res = ConfigBuilderWires::make()
    ->addCheckerDetails(Swear::NAME, $swearConfig)
    ->addCheckerDetails(Sqli::NAME, $sqliConfig)
    ->addCheckerDetails(Lfi::NAME, $lfiConfig)
    ->addCheckerDetails(Rfi::NAME, $rfiConfig)
    ->addCheckerDetails(Session::NAME, $sessionConfig)
    ->addCheckerDetails(Xss::NAME, $xssConfig)
    ->addCheckerDetails(Bot::NAME, $botConfig)
    ->addCheckerDetails(Php::NAME, $phpConfig)
    ->addCheckerDetails(Agent::NAME, $agentConfig)
    ->addCheckerDetails(Geo::NAME, $geoConfig)
    ->addCheckerDetails(Text::NAME, $textConfig)
    ->addCheckerDetails(RequestSize::NAME, $requestSizeConfig)
    ->addCheckerDetails('page404', $pageMissingConfig)
    ->addCheckerDetails('model404', $modelMissingConfig)
    ->addCheckerDetails('loginfailed', $loginFailedConfig)
    ->addCheckerDetails('throttle', $throttleHitConfig)
    ->addCheckerDetails('referer', $refererConfig)

    ->toArray();

//dd($lfiConfig);
return $res;
