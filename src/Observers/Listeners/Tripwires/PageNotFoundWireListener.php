<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\Page404FailedEvent;

class PageNotFoundWireListener extends WireBaseListener
{
    public const NAME = 'page404';

    public function __construct()
    {
        parent::__construct('page404');
    }

    public function isAttack(Event $event): bool
    {
        $violations = [];
        $url = $event->request->fullUrl();

        $violations[] = $url;

        if (! empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $this->config->attackScore(),
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
        event(new Page404FailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
