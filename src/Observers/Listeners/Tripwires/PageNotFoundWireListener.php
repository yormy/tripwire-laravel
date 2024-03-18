<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Observers\Listeners\Tripwires;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Event;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Observers\Events\Failed\Page404FailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\PageNotFoundEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\RouteModelBindingFailedEvent;

class PageNotFoundWireListener extends WireBaseListener
{
    public const NAME = 'page404';

    public function __construct()
    {
        parent::__construct('page404');
    }

    public function isAttack(Event | Failed $event): bool
    {
        $violations = [];

        /** @var PageNotFoundEvent $event */
        $url = $event->request->fullUrl();

        $violations[] = $url;

        if (! count($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $this->config->attackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $this->config->trainingMode(),
                debugMode: $this->config->debugMode(),
                comments: '',
                request: $this->request,
            );

            $this->attackFound($triggerEventData);
        }

        return ! count($violations);
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        event(new Page404FailedEvent($triggerEventData));

        $this->blockIfNeeded();
    }
}
