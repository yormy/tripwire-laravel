<?php
namespace Yormy\TripwireLaravel\DataObjects;

use \Illuminate\Contracts\Support\Arrayable;
use Yormy\TripwireLaravel\DataObjects\Config\ConfigDatetimeOption;
use Yormy\TripwireLaravel\DataObjects\Config\MailNotificationConfig;


class ConfigBuilder implements Arrayable
{
    protected bool $enabled;
    private bool $trainingMode;

    private bool $debugMode;

    public bool $notMode;

    public ConfigDatetimeOption $datetime;

    public MailNotificationConfig $notificationsMail;

    public function toArray(): array
    {
        $data = [
            'enabled' => $this->enabled,
            'training_mode' => $this->trainingMode,
            'debug_mode' => $this->debugMode ?? false,
        ];

        $data['datetime'] = $this->datetime->toArray();

        $data['notifications']['mail'] = $this->notificationsMail->toArray();

        if (isset($this->notMode)) {
            $data['not_mode'] = $this->notMode;
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
            $mail['template'],
            $mail['temmplatePlain'],
        );

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
