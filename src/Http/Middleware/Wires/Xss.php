<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Wires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\XssFailedEvent;

class Xss extends BaseWire
{
    public const NAME = 'xss';

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new XssFailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }

    public function prepareInput($value): string
    {
        $whitelistedTokens = $this->config->whitelistedTokens();// $this->getWhitelistedTokens();
        foreach ($whitelistedTokens as $token) {
            $value = str_ireplace($token,'##whitelisted_token_replacement##', $value);
        }

        return $value;
    }
}
