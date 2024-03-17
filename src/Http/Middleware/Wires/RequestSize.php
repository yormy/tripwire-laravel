<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\RequestSizeFailedEvent;

class RequestSize extends BaseWire
{
    public const NAME = 'request_size';

    public function isAttack($patterns): bool
    {
        $inputs = $this->request->input();
        $violations = [];
        $this->check($inputs, $violations);

        if (! empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $this->getAttackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $this->config->trainingMode(),
                debugMode: $this->config->debugMode(),
                comments: '',
            );

            $this->attackFound($triggerEventData);
        }

        return ! empty($violations);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new RequestSizeFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    private function check(array $inputs, array &$violations): void
    {
        foreach ($inputs as $field => $value) {
            if (is_array($value)) {
                $this->check($value, $violations);
            }

            if (strlen($value) > $this->config->tripwires()['size'] ?? 400) {
                $violations[] = $field;
            }
        }
    }
}
