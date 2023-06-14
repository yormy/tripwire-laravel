<?php
namespace Yormy\TripwireLaravel\DataObjects;

use \Illuminate\Contracts\Support\Arrayable;
use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerGroupConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ChecksumsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\DatetimeConfig;
use Yormy\TripwireLaravel\DataObjects\Config\CookiesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\DatabaseTablesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HoneypotsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputIgnoreConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\LoggingConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ModelsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ResetConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ServicesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WhitelistConfig;


class ConfigBuilder implements Arrayable
{
    protected bool $enabled;

    public int $blockCode;
    private bool $trainingMode;

    private bool $debugMode;

    public DatetimeConfig $datetime;

    public ?NotificationMailConfig $notificationsMail;
    public ?NotificationSlackConfig $notificationsSlack;

    public ChecksumsConfig $checksums;

    public DatabaseTablesConfig $databaseTables;

    public ModelsConfig $models;

    public CookiesConfig $cookies;

    public ServicesConfig $services;

    public LoggingConfig $logging;

    public InputIgnoreConfig $inputIgnore;

    public HoneypotsConfig $honeypots;

    public UrlsConfig $urls;

    public ResetConfig $reset;

    public WhitelistConfig $whitelist;

    public BlockResponseConfig $blockResponse;

    public PunishConfig $punish;

    public array $checkerGroups;

    public BlockResponseConfig $triggerResponse;


    public function toArray(): array
    {
        $data = [];
        $data['enabled'] = $this->enabled;
        $data['training_mode'] = $this->trainingMode;
        $data['debug_mode'] =$this->debugMode ?? false;

        $data['block_code'] = $this->blockCode;

        $data['datetime'] = $this->datetime->toArray();

        $data['notifications']['mail'] = $this->notificationsMail->toArray();

        if (isset($this->notificationsSlack)) {
            $data['notifications']['slack'] = $this->notificationsSlack->toArray();
        }

        if (isset($this->checksums)) {
            $data['checksums'] = $this->checksums->toArray();
        }

        if (isset($this->databaseTables)) {
            $data['database_tables'] = $this->databaseTables->toArray();
        }

        if (isset($this->models)) {
            $data['models'] = $this->models->toArray();
        }

        if (isset($this->cookies)) {
            $data['cookies'] = $this->cookies->toArray();
        }

        if (isset($this->services)) {
            $data['services'] = $this->services->toArray();
        }

        if (isset($this->logging)) {
            $data['log'] = $this->logging->toArray();
        }

        if (isset($this->inputIgnore)) {
            $data['input'] = $this->inputIgnore->toArray();
        }

        if (isset($this->honeypots)) {
            $data['honeypots'] = $this->honeypots->toArray();
        }

        if (isset($this->urls)) {
            $data['urls'] = $this->urls->toArray();
        }

        if (isset($this->reset)) {
            $data['reset'] = $this->reset->toArray();
        }

        if (isset($this->whitelist)) {
            $data['whitelist'] = $this->whitelist->toArray();
        }

        if (isset($this->blockResponse)) {
            $data['block_response'] = $this->blockResponse->toArray();
        }

        if (isset($this->checkerGroups)) {

            foreach ($this->checkerGroups as $name => $checkerGroup) {
                $data['checker_groups'][$name] = $checkerGroup->toArray();
            }
        }

        if (isset($this->punish)) {
            $data['punish'] = $this->punish->toArray();
        }

        if (isset($this->triggerResponse)) {
            $data['trigger_response'] = $this->triggerResponse->toArray();
        }

        return $data;
    }



    public static function fromArray(array $data)
    {
        $config = new self();

        $config->enabled($data['enabled']);
        $config->blockCode($data['block_code']);

        if (isset($data['training_mode'])) {
            $config->trainingMode($data['training_mode']);
        }

        $config->datetime = DatetimeConfig::makeFromArray($data['datetime'] ?? null);

        $config->notificationsMail = NotificationMailConfig::makeFromArray($data['notifications']['mail'] ?? null);

        $config->notificationsSlack = NotificationSlackConfig::makeFromArray($data['notifications']['slack'] ?? null);

        $config->checksums = ChecksumsConfig::makeFromArray($data['checksums'] ?? null);

        $config->databaseTables = DatabaseTablesConfig::makeFromArray($data['database_tables'] ?? null);

        $config->models = ModelsConfig::makeFromArray($data['models'] ?? null);

        $config->cookies = CookiesConfig::makeFromArray($data['cookies'] ?? null);

        $config->services = ServicesConfig::makeFromArray($data['services'] ?? null);

        $config->logging = LoggingConfig::makeFromArray($data['log'] ?? null);

        $config->inputIgnore = InputIgnoreConfig::makeFromArray($data['input'] ?? null);

        $config->honeypots = HoneypotsConfig::makeFromArray($data['honeypots'] ?? null);

        $config->urls = UrlsConfig::makeFromArray($data['urls'] ?? null);

        $config->reset = ResetConfig::makeFromArray($data['reset'] ?? null);

        $config->whitelist = WhitelistConfig::makeFromArray($data['whitelist'] ?? null);

        $config->blockResponse = BlockResponseConfig::makeFromArray($data['block_response'] ?? null);

        $config->checkerGroups = CheckerGroupConfig::makeFromArray($data['checker_groups'] ?? null);

        $config->punish = PunishConfig::makeFromArray($data['punish'] ?? null);

        $config->triggerResponse = BlockResponseConfig::makeFromArray($data['trigger_response'] ?? null);

        return $config;
    }

    /*
    |--------------------------------------------------------------------------
    | Enable the tripwire system
    |--------------------------------------------------------------------------
    | When disabled, nothing happens
    |
    */
    public function enabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function blockCode(int $blockCode): self
    {
        $this->blockCode = $blockCode;

        return $this;
    }

    public function trainingMode(bool $trainingMode): self
    {
        $this->trainingMode = $trainingMode;

        return $this;
    }

    public function debugMode(bool $debugMode): self
    {
        $this->debugMode = $debugMode;

        return $this;
    }

    public function notificationMail(
        NotificationMailConfig $notificationMail,
    ): self {
        $this->notificationsMail = $notificationMail;

        return $this;
    }

    public function notificationSlack(
        NotificationSlackConfig $notificationsSlack,
    ): self {
        $this->notificationsSlack = $notificationsSlack;
        return $this;
    }


    public function checksums(
        ChecksumsConfig $checksums,
    ): self {
        $this->checksums = $checksums;

        return $this;
    }

    public function databaseTables(
        string $tripwireLogs,
        string $tripwireBlocks,
    ): self {
        $this->databaseTables = DatabaseTablesConfig::make(
            $tripwireLogs,
            $tripwireBlocks,
        );

        return $this;
    }

    public function models(
        string $models,
    ): self {
        $this->models = ModelsConfig::make(
            $models,
        );

        return $this;
    }

    public function cookies(
        string $browserFingerprint,
    ): self {
        $this->cookies = CookiesConfig::make(
            $browserFingerprint,
        );

        return $this;
    }

    public function services(
        string $requestSource,
        string $user,
        string $ipAddress,
    ): self {
        $this->services = ServicesConfig::make(
            $requestSource,
            $user,
            $ipAddress
        );

        return $this;
    }

    public function logging(
        LoggingConfig $logging,
    ): self {
        $this->logging = $logging;

        return $this;
    }

    public function inputIgnore(
        InputIgnoreConfig $inputIgnore,
    ): self {
        $this->inputIgnore = $inputIgnore;

        return $this;
    }

    public function honeypots(
        array $mustBeMissingOrFalse,
    ): self {
        $this->honeypots = HoneypotsConfig::make(
            $mustBeMissingOrFalse,
        );

        return $this;
    }

    public function urls(
        array $except,
    ): self {
        $this->urls = UrlsConfig::make(
            $except,
        );

        return $this;
    }

    public function reset(
        ResetConfig $resetConfig,
    ): self {
        $this->reset = $resetConfig;

        return $this;
    }

    public function whitelist(
        array $ips,
    ): self {
        $this->whitelist = WhitelistConfig::make(
            $ips,
        );

        return $this;
    }

    public function blockResponse(
        JsonResponseConfig $jsonResponseConfig,
        HtmlResponseConfig $htmlResponseConfig
    ): self {
        $this->blockResponse = BlockResponseConfig::make(
            $jsonResponseConfig,
            $htmlResponseConfig
        );

        return $this;
    }

    public function addCheckerGroup(
        string $groupName,
        array $checkers,
    ): self {
        $this->checkerGroups[$groupName] = CheckerGroupConfig::make(
            $checkers,
        );

        return $this;
    }

    public function punish(
        int $score,
        int $withinMinutes,
        int $penaltySeconds
    ): self {
        $this->punish = PunishConfig::make(
            $score,
            $withinMinutes,
            $penaltySeconds,
        );

        return $this;
    }

    public function triggerResponse(
        JsonResponseConfig $jsonResponseConfig,
        HtmlResponseConfig $htmlResponseConfig
    ): self {
        $this->triggerResponse = BlockResponseConfig::make(
            $jsonResponseConfig,
            $htmlResponseConfig
        );

        return $this;
    }

    public function dateFormat(string $format, int $offset = 0): self
    {
        $this->datetime = DatetimeConfig::make($format, $offset);

        return $this;
    }

    public static function make(): self
    {
        return new self();
    }
}