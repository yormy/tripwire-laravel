<?php

use Mexion\BedrockUsers\Models\Admin;
use Mexion\BedrockUsers\Models\Member;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerGroupConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ChecksumsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputIgnoreConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\LoggingConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ResetConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\IpAddress;
use Yormy\TripwireLaravel\Services\RequestSource;
use Yormy\TripwireLaravel\Services\User;

$res = ConfigBuilder::make()
    ->enabled(env('TRIPWIRE_ENABLED', true))
    ->trainingMode(env('FIREWALL_EMAIL_ENABLED', true))

    ->trainingMode(false)
    ->dateFormat('Y-m-f', 0)
    ->notificationMail(
        NotificationMailConfig::make(env('FIREWALL_EMAIL_ENABLED', true))
            ->name(env('FIREWALL_EMAIL_NAME', 'Laravel Firewall'))
            ->from(env('FIREWALL_EMAIL_FROM', 'firewall@mydomain.com'))
            ->to(env('FIREWALL_EMAIL_TO', 'admin@mydomain.com'))
            ->templatePlain(env('FIREWALL_EMAIL_TO', 'tripwire-laravel::email_plain'))
            ->templateHtml(env('FIREWALL_EMAIL_TO', 'tripwire-laravel::email'))
    )

    ->notificationSlack(
        NotificationSlackConfig::make(env('FIREWALL_SLACK_ENABLED', false))
            ->from(env('FIREWALL_SLACK_FROM', 'Tripwire'))
            ->to(env('FIREWALL_SLACK_TO',''))
            ->channel(env('FIREWALL_SLACK_CHANNEL', 'ttt'))
            ->emoji(env('FIREWALL_SLACK_EMOJI', ':japanese_goblin:'))
    )

    ->checksums(
        ChecksumsConfig::make()
            ->posted('X-Checksum')
            ->timestamp('X-sand')
            ->serversideCalculated('x-checksum-serverside')
    )

    ->databaseTables(
        'tripwire_logs',
        'tripwire_blocks'
    )
    ->models(TripwireLog::class)
    ->cookies('session_id')

    ->services(
        RequestSource::class,
        User::class,
        IpAddress::class
    )
    ->logging(LoggingConfig::make()->remove(['remove']))
    ->inputIgnore(InputIgnoreConfig::make()->cookies(['session_id']))
    ->honeypots([
        'isAdmin',
        'debug',
        'logged_in',
        'is_admin',
        'is_debug',
        'show_log',
        'skip_encryption',
    ])
  //  ->urls(UrlsConfig::make())
    ->reset(
        ResetConfig::make(env('TRIPWIRE_WHITELIST', true))
            ->softDelete(true)
            ->linkExpireMinutes(30)
    )

    ->whitelist(explode(',', env('TRIPWIRE_WHITELIST', '')))
    ->blockCode(409)

    ->blockResponse(
        JsonResponseConfig::make()->code(506)->abort(env('FIREWALL_BLOCK_ABORT', false)),
        HtmlResponseConfig::make()->view('tripwire-laravel::blocked'),
        )

    ->addCheckerGroup('all',
        CheckerGroupConfig::make([
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
        ])
    )

    ->addCheckerGroup('user',
        CheckerGroupConfig::make([
            'tripwire.lfi',
            'tripwire.php',
            'tripwire.rfi',
            'tripwire.sqli',
            'tripwire.swear',
            'tripwire.text',
            'tripwire.xss',
        ])
    )

    ->addCheckerGroup('server',
        CheckerGroupConfig::make([
            'tripwire.agent',
            'tripwire.bot',
            'tripwire.geo',
            'tripwire.referer',
            'tripwire.session',
        ])
    )

    ->punish(800, 60*24, 5)

    ->triggerResponse(
        JsonResponseConfig::make(
            506,
            env('FIREWALL_BLOCK_ABORT', false),
            ['err'=> 'l'],
            'exception',
            'message.key'
        ),
        HtmlResponseConfig::make(
        null,
        'tripwire-laravel::blocked',
            TripwireFailedException::class,
        'message.key'
        ),
    )
    ->toArray();

return $res;
