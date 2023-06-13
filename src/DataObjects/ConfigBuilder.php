<?php
namespace Yormy\TripwireLaravel\DataObjects;

use \Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Yormy\TripwireLaravel\DataObjects\Config\ChecksumsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ConfigDatetimeOption;
use Yormy\TripwireLaravel\DataObjects\Config\CookiesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\DatabaseTablesConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MailNotificationConfig;
use Yormy\TripwireLaravel\DataObjects\Config\ModelsConfig;
use Yormy\TripwireLaravel\DataObjects\Config\SlackNotificationConfig;


class ConfigBuilder implements Arrayable
{
    protected bool $enabled;
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

    public function toArray(): array
    {
        $data = [
            'enabled' => $this->enabled,
            'training_mode' => $this->trainingMode,
            'debug_mode' => $this->debugMode ?? false,
        ];

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




        return $data;
    }



    public static function fromArray(array $data)
    {
        $config = new self();

        $config->enabled($data['enabled']);

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

        if (isset($data['database_tables'])) {
            $tables = $data['database_tables'];
            $config->databaseTables(
                $tables['tripwire_logs'],
                $tables['tripwire_blocks'],
            );
        }

        if (isset($data['models'])) {
            $models = $data['models'];
            $config->models(
                $models['log'],
            );
        }

        $config->cookies = CookiesConfig::makeFromArray($data['cookies'] ?? null);

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
        $this->databaseTables = new DatabaseTablesConfig(
            $tripwireLogs,
            $tripwireBlocks,
        );

        return $this;
    }

    public function models(
        string $models,
    ): self {
        $this->models = new ModelsConfig(
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
