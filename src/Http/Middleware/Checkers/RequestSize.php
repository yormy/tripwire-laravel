<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Yormy\TripwireLaravel\Observers\Events\Failed\RequestSizeFailedEvent;

class RequestSize extends BaseChecker
{
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

    protected function attackFound(array $violations): void
    {
        event(new RequestSizeFailedEvent(
            attackScore: $this->getAttackScore(),
            violations: $violations
        ));

        $this->blockIfNeeded();
    }

}