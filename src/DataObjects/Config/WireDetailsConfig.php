<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\DataObjects\Config;

class WireDetailsConfig
{
    public bool $enabled;

    public ?bool $trainingMode;

    /**
     * @var array<string>|null
     */
    public ?array $methods;

    public int $attackScore;

    public ?UrlsConfig $urls;

    public ?InputsFilterConfig $inputs;

    /**
     * @var array<string>|null
     */
    public ?array $tripwires;

    /**
     * @var array<string>|null
     */
    public ?array $filters;

    /**
     * @var array<string>|null
     */
    public ?array $config;

    public ?AllowBlockFilterConfig $allowBlockConfig;

    public ?PunishConfig $punish;

    public ?BlockResponseConfig $rejectResponse;

    /**
     * @var array<string>
     */
    public array $whitelistedTokens = [];

    private function __construct()
    {
        // disable default constructor
    }

    /**
     * @param  array<string>  $methods
     * @param  array<string>  $tripwires
     * @param  array<string>  $config
     */
    public static function make(
        bool $enabled = true,
        ?bool $trainingMode = null,
        array $methods = ['*'],
        int $attackScore = 0,
        ?UrlsConfig $urlsConfig = null,
        ?InputsFilterConfig $inputs = null,
        array $tripwires = [],
        ?AllowBlockFilterConfig $allowBlockConfig = null,
        ?PunishConfig $punishConfig = null,
        ?BlockResponseConfig $rejectResponse = null,
        array $config = [],
    ): self {
        $object = new WireDetailsConfig();

        $object->enabled = $enabled;

        if (isset($trainingMode)) {
            $object->trainingMode = $trainingMode;
        }

        if ($methods) {
            $errors = $object->getArrayErrors($methods, ['*', 'all', 'post', 'put', 'patch', 'get', 'delete']);
            if ($errors) {
                throw new \Exception('Invalid method defined for :'.implode(',', $errors));
            }
        }

        $object->methods = $methods;
        $object->attackScore = $attackScore;
        $object->urls = $urlsConfig;
        $object->inputs = $inputs;
        $object->tripwires = $tripwires;
        $object->allowBlockConfig = $allowBlockConfig;
        $object->punish = $punishConfig;
        $object->rejectResponse = $rejectResponse;

        $object->config = $config;

        return $object;
    }

    public static function makeFromArray(?array $data): ?self
    {
        if (! $data) {
            return null;
        }

        $object = new WireDetailsConfig();

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

        if (isset($data['whitelisted_tokens'])) {
            $object->whitelistedTokens = $data['whitelisted_tokens'];
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

        if (isset($data['filters'])) {
            $object->filters = $data['filters'];
        }

        if (isset($data['config'])) {
            $object->config = $data['config'];
        }

        if (isset($data['punish'])) {
            $object->punish = $data['punish'];
        }

        if (isset($data['reject_response'])) {
            $object->rejectResponse = BlockResponseConfig::makeFromArray($data['reject_response']);
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

    /**
     * @param  array<string>  $methods
     */
    public function methods(array $methods): self
    {
        if (! $methods) {
            return $this;
        }

        $errors = $this->getArrayErrors($methods, ['*', 'all', 'post', 'put', 'patch', 'get', 'delete']);
        if ($errors) {
            throw new \Exception('Invalid method defined for :'.implode(',', $errors));
        }

        $this->methods = $methods;

        return $this;
    }

    public function attackScore(int $attackScore): self
    {
        $this->attackScore = $attackScore;

        return $this;
    }

    /**
     * @param  array<string>  $whitelistedTokens
     */
    public function whitelistedTokens(array $whitelistedTokens): self
    {
        $this->whitelistedTokens = $whitelistedTokens;

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

    /**
     * @param  array<string>  $tripWires
     */
    public function tripwires(array $tripWires): self
    {
        $this->tripwires = $tripWires;

        return $this;
    }

    public function filters(AllowBlockFilterConfig $allowBlockConfig): self
    {
        $this->allowBlockConfig = $allowBlockConfig;

        return $this;
    }

    /**
     * @param  array<string>  $config
     */
    public function config(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function punish(PunishConfig $punish): self
    {
        $this->punish = $punish;

        return $this;
    }

    public function rejectResponse(BlockResponseConfig $rejectResponse): self
    {
        $this->rejectResponse = $rejectResponse;

        return $this;
    }

    /**
     * @return array<string>
     */
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

        $data['whitelisted_tokens'] = $this->whitelistedTokens;

        if ($this->urls) {
            $data['urls'] = $this->urls->toArray();
        }

        if ($this->inputs) {
            $data['inputs'] = $this->inputs->toArray();
        }

        $data['tripwires'] = $this->tripwires;

        if (isset($this->allowBlockConfig)) {
            $data['filters'] = $this->allowBlockConfig->toArray();
        }

        if ($this->config) {
            $data['config'] = $this->config;
        }

        if ($this->punish) {
            $data['punish'] = $this->punish->toArray();
        }

        if ($this->rejectResponse) {
            $data['reject_response'] = $this->rejectResponse->toArray();
        }

        return $data;
    }

    /**
     * @param  array<string>  $values
     * @param  array<string>  $allowedValues
     * @return array<string, array<string>|bool|int|null>
     */
    private function getArrayErrors(array $values, array $allowedValues): array
    {
        $errors = [];
        foreach ($values as $value) {
            if (! in_array($value, $allowedValues)) {
                $errors[] = $value;
            }
        }

        return $errors;
    }
}
