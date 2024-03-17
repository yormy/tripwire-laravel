<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects;

use Illuminate\Contracts\Support\Arrayable;
use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\CookiesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\DatabaseTablesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\DatetimeConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputIgnoreConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\LoggingConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ModelsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationMailConfig;
use Yormy\TripwireLaravel\DataObjects\Config\NotificationSlackConfig;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ResetConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ServicesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WhitelistConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireGroupConfig;

class ConfigBuilder implements Arrayable
{
    public bool $enabled;

    public int $blockCode;

    public ?bool $trainingMode;

    public bool $debugMode;

    public DatetimeConfig $datetime;

    public array $notificationsMail;

    public array $notificationsSlack;

    public DatabaseTablesConfig $databaseTables;

    public ModelsConfig $models;

    public CookiesConfig $cookies;

    public ServicesConfig $services;

    public LoggingConfig $logging;

    public InputIgnoreConfig $inputIgnore;

    public ?UrlsConfig $urls;

    public ResetConfig $reset;

    public WhitelistConfig $whitelist;

    public BlockResponseConfig $blockResponse;

    public PunishConfig $punish;

    public array $wireGroups;

    public BlockResponseConfig $rejectResponse;

    public function toArray(): array
    {
        $data = [];
        $data['enabled'] = $this->enabled;

        if (isset($this->trainingMode)) {
            $data['training_mode'] = $this->trainingMode;
        }

        $data['debug_mode'] = $this->debugMode ?? false;

        $data['block_code'] = $this->blockCode;

        $data['datetime'] = $this->datetime->toArray();

        if (isset($this->notificationsMail)) {
            foreach ($this->notificationsMail as $mailSetting) {
                $data['notifications']['mail'][] = $mailSetting->toArray();
            }
        }

        if (isset($this->notificationsSlack)) {
            foreach ($this->notificationsSlack as $mailSetting) {
                $data['notifications']['slack'][] = $mailSetting->toArray();
            }
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
            $data['ignore'] = $this->inputIgnore->toArray();
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

        if (isset($this->wireGroups)) {
            foreach ($this->wireGroups as $name => $wireGroup) {
                $data['wire_groups'][$name] = $wireGroup->toArray();
            }
        }

        if (isset($this->punish)) {
            $data['punish'] = $this->punish->toArray();
        }

        if (isset($this->rejectResponse)) {
            $data['reject_response'] = $this->rejectResponse->toArray();
        }

        return $data;
    }

    /**
     * @param array<string> $data
     */
    public static function fromArray(array $data): self
    {
        $config = new self();

        $config->enabled($data['enabled']);
        $config->blockCode($data['block_code']);

        if (isset($data['training_mode'])) {
            $config->trainingMode($data['training_mode']);
        }

        if (isset($data['debug_mode'])) {
            $config->debugMode($data['debug_mode']);
        }

        $config->datetime = DatetimeConfig::makeFromArray($data['datetime'] ?? null);

        if (isset($data['notifications']['mail'])) {
            foreach ($data['notifications']['mail'] as $mailSetting) {
                $config->notificationsMail[] = NotificationMailConfig::makeFromArray($mailSetting);
            }
        }

        if (isset($data['notifications']['slack'])) {
            foreach ($data['notifications']['slack'] as $mailSetting) {
                $config->notificationsSlack[] = NotificationSlackConfig::makeFromArray($mailSetting);
            }
        }

        $config->databaseTables = DatabaseTablesConfig::makeFromArray($data['database_tables'] ?? null);

        $config->models = ModelsConfig::makeFromArray($data['models'] ?? null);

        $config->cookies = CookiesConfig::makeFromArray($data['cookies'] ?? null);

        $config->services = ServicesConfig::makeFromArray($data['services'] ?? null);

        $config->logging = LoggingConfig::makeFromArray($data['log'] ?? null);

        $config->inputIgnore = InputIgnoreConfig::makeFromArray($data['ignore'] ?? null);

        $config->urls = UrlsConfig::makeFromArray($data['urls'] ?? null);

        $config->reset = ResetConfig::makeFromArray($data['reset'] ?? null);

        $config->whitelist = WhitelistConfig::makeFromArray($data['whitelist'] ?? null);

        $config->blockResponse = BlockResponseConfig::makeFromArray($data['block_response'] ?? null);

        $config->wireGroups = WireGroupConfig::makeFromArray($data['wire_groups'] ?? null);

        $config->punish = PunishConfig::makeFromArray($data['punish'] ?? null);

        $config->rejectResponse = BlockResponseConfig::makeFromArray($data['reject_response'] ?? null);

        return $config;
    }

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

    /**
     * @param array<string> $notificationMail
     */
    public function notificationMail(array $notificationMail): self
    {
        $this->notificationsMail = $notificationMail;

        return $this;
    }

    /**
     * @param array<string> $notificationsSlack
     */
    public function notificationSlack(array $notificationsSlack): self
    {
        $this->notificationsSlack = $notificationsSlack;

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
        string $logModel,
        string $blockModel,
    ): self {
        $this->models = ModelsConfig::make(
            $logModel,
            $blockModel
        );

        return $this;
    }

    public function browserFingerprint(
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

    public function urls(
        UrlsConfig $urls,
    ): self {
        $this->urls = $urls;

        return $this;
    }

    public function reset(
        ResetConfig $resetConfig,
    ): self {
        $this->reset = $resetConfig;

        return $this;
    }

    /**
     * @param array<string> $ips
     */
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

    public function addWireGroup(
        string $groupName,
        WireGroupConfig $wireGroupConfig,
    ): self {
        $this->wireGroups[$groupName] = $wireGroupConfig;

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

    public function rejectResponse(
        JsonResponseConfig $jsonResponseConfig,
        HtmlResponseConfig $htmlResponseConfig
    ): self {
        $this->rejectResponse = BlockResponseConfig::make(
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
