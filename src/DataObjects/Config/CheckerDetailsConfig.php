<?php

namespace Yormy\TripwireLaravel\DataObjects\Config;

class CheckerDetailsConfig
{
    public bool $enabled;

    public ?bool $trainingMode;

    public ?array $methods;

    public int $attackScore;

    public ?UrlsConfig $urls;

    public ?InputsFilterConfig $inputs;

    public array $tripwires;

    public ?array $guards;

    public ?PunishConfig $punish;

    public ?BlockResponseConfig $triggerResponse;

    private function __construct()
    {}

    public static function make(
        bool $enabled = true,
        bool $trainingMode = null,
        array $methods = ['*'],
        int $attackScore = 0,
        UrlsConfig $urlsConfig = null,
        InputsFilterConfig $inputs  = null,
        array $tripwires = [],
        array $guards = [],
        PunishConfig $punishConfig  = null,
        BlockResponseConfig $triggerResponse  = null,
    ): self {
        $object = new CheckerDetailsConfig();

        $object->enabled = $enabled;

        if (isset($trainingMode)) {
            $object->trainingMode = $trainingMode;
        }

        if ($methods) {
            $errors = $object->getArrayErrors($methods, ['*','all','post', 'put', 'patch', 'get','delete']);
            if ($errors) {
                throw new \Exception("Invalid method defined for :". implode(',', $errors) );
            }
        }

        $object->methods = $methods;
        $object->attackScore = $attackScore;
        $object->urls = $urlsConfig;
        $object->inputs = $inputs;
        $object->tripwires = $tripwires;
        $object->guards = $guards;
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
        if (isset($data['training_mode'])) {
            $object->trainingMode = $data['training_mode'];
        }

        if (isset($data['methods'])) {
            $object->methods = $data['methods'];
        }

        if (isset($data['attack_score'])) {
            $object->attackScore = $data['attack_score'];
        }

        if (isset($data['urls'])) {
            $object->urls = UrlsConfig::makeFromArray($data['urls']);
        }

        if (isset($data['inputs'])) {
            $object->inputs = $data['inputs'];
        }

        if (isset($data['tripwires'])) {
            $object->tripwires = $data['tripwires'];
        }

        if (isset($data['guards'])) {
            $object->guards = $data['guards'];
        }

        if (isset($data['punish'])) {
            $object->punish = $data['punish'];
        }

        if (isset($data['trigger_response'])) {
            $object->triggerResponse = BlockResponseConfig::makeFromArray($data['trigger_response']);
        }

       return $object;
    }

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

    public function methods(array $methods): self
    {
        if (!$methods) {
            return $this;
        }

        $errors = $this->getArrayErrors($methods, ['*','all','post', 'put', 'patch', 'get','delete']);
        if ($errors) {
            throw new \Exception("Invalid method defined for :". implode(',', $errors) );
        }

        $this->methods = $methods;

        return $this;
    }

    public function attackScore(int $attackScore): self
    {
        $this->attackScore = $attackScore;

        return $this;
    }

    public function urls(UrlsConfig $urls): self
    {
        $this->urls = $urls;

        return $this;
    }

    public function inputFilter(InputsFilterConfig $inputFilter): self
    {
        $this->inputs = $inputFilter;

        return $this;
    }

    public function tripwires(array $tripWires): self
    {
        $this->tripwires = $tripWires;

        return $this;
    }

    public function guards(array $guards): self
    {
        $this->guards = $guards;

        return $this;
    }

    public function punish(PunishConfig $punish): self
    {
        $this->punish = $punish;

        return $this;
    }

    public function triggerResponse(BlockResponseConfig $triggerResponse): self
    {
        $this->triggerResponse = $triggerResponse;

        return $this;
    }


    public function toArray(): array
    {
        $data = [];

        $data['enabled'] = $this->enabled;

        if (isset($this->trainingMode)) {
            $data['training_mode'] = $this->trainingMode;
        }

        if ($this->methods) {
            $data['methods'] = $this->methods;
        }

        $data['attack_score'] = $this->attackScore;

        if ($this->urls) {
            $data['urls'] = $this->urls->toArray();
        }

        if ($this->inputs) {
            $data['inputs'] = $this->inputs->toArray();
        }

        $data['tripwires'] = $this->tripwires;

        if ($this->guards) {
            $data['guards'] = $this->guards;
        }

        if ($this->punish) {
            $data['punish'] = $this->punish->toArray();
        }

        if ($this->triggerResponse) {
            $data['trigger_response'] = $this->triggerResponse->toArray();
        }

        return $data;
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
