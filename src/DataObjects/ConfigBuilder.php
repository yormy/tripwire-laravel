<?php
namespace Yormy\TripwireLaravel\DataObjects;

use http\Url;
use \Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\CheckerGroupConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ChecksumsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ConfigDatetimeOption;
use Yormy\TripwireLaravel\DataObjects\Config\CookiesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\DatabaseTablesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HoneypotsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\InputIgnoreConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\LoggingConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MailNotificationConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ModelsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\PunishConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ResetConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ServicesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\SlackNotificationConfig;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WhitelistConfig;


class ConfigBuilder implements Arrayable
{
    protected bool $enabled;

    public int $blockCode;
    private bool $trainingMode;

    private bool $debugMode;

    public bool $notMode;

    public ConfigDatetimeOption $datetime;

    public MailNotificationConfig $notificationsMail;
    public SlackNotificationConfig $notificationsSlack;

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
        $data = [
            'enabled' => $this->enabled,
            'training_mode' => $this->trainingMode,
            'debug_mode' => $this->debugMode ?? false,
        ];

        $data['block_code'] = $this->blockCode;

        $data['datetime'] = $this->datetime->toArray();

        $data['notifications']['mail'] = $this->notificationsMail->toArray();

        if (isset($this->notificationsSlack)) {
            $data['notifications']['slack'] = $this->notificationsSlack->toArray();
        }

        if (isset($this->notMode)) {
            $data['not_mode'] = $this->notMode;
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

        if (isset($data['not_mode'])) {
            $config->notMode($data['not_mode']);
        }

        $config->dateFormat($data['datetime']['format'], $data['datetime']['offset']);

        $mail = $data['notifications']['mail'];
        $config->notificationMail(
            $mail['enabled'],
            $mail['name'],
            $mail['from'],
            $mail['to'],
            $mail['template_html'],
            $mail['temmplate_plain'],
        );

        if (isset($data['notifications']['slack'])) {
            $slack = $data['notifications']['slack'];
            $config->notificationSlack(
                $slack['enabled'],
                $slack['from'],
                $slack['to'],
                $slack['emoji'],
                $slack['channel'],
            );
        }

        if (isset($data['checksums'])) {
            $checksums = $data['checksums'];
            $config->checksums(
                $checksums['posted'],
                $checksums['timestamp'],
                $checksums['serverside_calculated'],
            );
        }

        $config->databaseTables = DatabaseTablesConfig::makeFromArray($data['database_tables'] ?? null);

        $config->models = ModelsConfig::makeFromArray($data['models'] ?? null);

        //===============================================================================
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
        bool   $enabled,
        string $name,
        string $from,
        string $to,
        string $template,
        string $templatePlain,
    ): self {
        $this->notificationsMail = new MailNotificationConfig(
            $enabled,
            $name,
            $from,
            $to,
            $template,
            $templatePlain,
        );

        return $this;
    }

    public function notificationSlack(
        bool $enabled,
        string $from,
        string $to,
        string $emoji,
        ?string $channel,
    ): self {
        if (!$enabled) {
            return $this;
        }

        $this->notificationsSlack = new SlackNotificationConfig(
            $enabled,
            $from,
            $to,
            $emoji,
            $channel,
        );

        return $this;
    }


    public function checksums(
        string $posted,
        string $timestamp,
        string $serversideCalculated,
    ): self {
        $this->checksums = new ChecksumsConfig(
            $posted,
            $timestamp,
            $serversideCalculated,
        );

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
        string $maxRequestSize,
        string $maxHeaderSize,
        string $maxRefererSize,
        array $remove,
    ): self {
        $this->logging = LoggingConfig::make(
            $maxRequestSize,
            $maxHeaderSize,
            $maxRefererSize,
            $remove
        );

        return $this;
    }

    public function inputIgnore(
        array $input,
        array $cookies,
        array $header
    ): self {
        $this->inputIgnore = InputIgnoreConfig::make(
            $input,
            $cookies,
            $header,
        );

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
        bool $enabled,
        bool $softDelete,
        int $linkExpireMintues,
    ): self {
        $this->reset = ResetConfig::make(
            $enabled,
            $softDelete,
            $linkExpireMintues
        );

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
        $this->datetime = new ConfigDatetimeOption($format, $offset);

        return $this;
    }
    public function notMode(bool $notMode): self
    {
        $this->notMode = $notMode;

        return $this;
    }


    public static function make(): self
    {
        $config = new self();

        return $config;
    }

    private function getArrayErrors(array $values, array $allowedValues): array
    {
        $errors = [];
        foreach ($values as $value)
        {
            if (!in_array($value, $allowedValues)) {
                $errors[] = $value;
            }
        }

        return $errors;
    }
}
