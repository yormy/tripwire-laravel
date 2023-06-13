<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class CheckerDetailsConfig
{
    public bool $enabled;

    public bool $trainingMode;

    public array $methods;

    public int $attackScore;

    public UrlsConfig $urls;

    public InputsFilterConfig $inputs;

    public array $tripwires;

    public PunishConfig $punish;

    public BlockResponseConfig $triggerResponse;

    private function __construct()
    {}

    public static function make(
        bool $enabled,
        bool $trainingMode,
        array $methods,
        int $attackScore,
        UrlsConfig $urlsConfig,
        InputsFilterConfig $inputs,
        array $tripwires,
        PunishConfig $punishConfig,
        BlockResponseConfig $triggerResponse,
    ): self {
        $object = new CheckerDetailsConfig();

        $object->enabled = $enabled;
        $object->trainingMode = $trainingMode;
        $object->methods = $methods;
        $object->attackScore = $attackScore;
        $object->urls = $urlsConfig;
        $object->inputs = $inputs;
        $object->tripwires = $tripwires;
        $object->punish = $punishConfig;
        $object->triggerResponse = $triggerResponse;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (!$data) {
            return null;
        }

        $object = new CheckerDetailsConfig();

        $object->enabled = $data['enabled'];
        $object->trainingMode = $data['training_mode'];
        $object->methods = $data['methods'];
        $object->attackScore = $data['attack_score'];
        $object->urls = $data['urls'];
        $object->inputs = $data['inputs'];
        $object->tripwires = $data['tripwires'];
        $object->punish = $data['punish'];
        $object->triggerResponse = $data['trigger_response'];

       return $object;
    }


    public function toArray(): array
    {
        return [
            'enabled' => $this->enabled,
            'trainingMode' => $this->trainingMode,
            'methods' => $this->methods,
            'attack_score' => $this->attackScore,
            'urls' => $this->urls->toArray(),
            'inputs' => $this->inputs->toArray(),
            'tripwires' => $this->tripwires,
            'punish' => $this->punish->toArray(),
            'trigger_response' => $this->triggerResponse->toArray(),
        ];
    }
}
