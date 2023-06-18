<?php

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

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    private function check($inputs, &$violations)
    {

        foreach($inputs as $field => $value)
        {
            if (is_array($value)) {
                $this->check($value, $violations);
            }

            if (strlen($value) > $this->config->tripwires['size'] ?? 400) {
                $violations[] = $field;
            }
        }
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new RequestSizeFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

}
