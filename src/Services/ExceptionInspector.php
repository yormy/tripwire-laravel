<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Yormy\TripwireLaravel\DataObjects\Config\MissingModelConfig;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\Model404FailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Failed\Page404FailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Failed\SessionFailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\ThrottleHitEvent;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

class ExceptionInspector
{
    use TripwireHelpers;

    protected WireConfig $config;

    public function inspect(Throwable $e, Request $request = null): void
    {
        if ($e instanceof ThrottleRequestsException) {
            event(new ThrottleHitEvent($request));
        }

        if ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();

            $this->config = new WireConfig('model404');

            if ($this->config->isDisabled()) {
                return;
            }

            /** @var MissingModelConfig $missingModelConfig */
            $missingModelConfig = $this->config->tripwires()[0];
            $needsProcessing = CheckOnlyExcept::needsProcessing($model, $missingModelConfig);
            if ($needsProcessing) {
                // attack found
                $violations = [$model];
                $triggerEventData = new TriggerEventData(
                    attackScore: $this->config->attackScore(),
                    violations: $violations,
                    triggerData: implode(',', $violations),
                    triggerRules: [],
                    trainingMode: $this->config->trainingMode(),
                    debugMode: $this->config->debugMode(),
                    comments: '',
                );
                event(new Model404FailedEvent($triggerEventData));

                BlockIfNeeded::run($request, $this->config->punish(), $this->config->trainingMode());
                // Response is not needed, consumer will handle the 404, this is just an additional inspector
            }
        }

        if ($e instanceof NotFoundHttpException) {
            $this->config = new WireConfig('page404');

            if ($this->skip($request)) {
                return;
            }

            $value = $request->url();
            $violations = [$value];
            $triggerEventData = new TriggerEventData(
                attackScore: $this->config->attackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $this->config->trainingMode(),
                debugMode: $this->config->debugMode(),
                comments: '',
            );
            event(new Page404FailedEvent($triggerEventData));

            BlockIfNeeded::run($request, $this->config->punish(), $this->config->trainingMode());
            // Response is not needed, consumer will handle the 404, this is just an additional inspector
        }
    }

    protected function attackFound(TriggerEventData $triggerEventData): void
    {
        // no additional processing needed here
    }
}
