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
use Yormy\TripwireLaravel\Observers\Events\Tripwires\ThrottleHitEvent;

class ExceptionInspector
{
    public static function inspect(Throwable $e, Request $request = null): void
    {
        if ($e instanceof ThrottleRequestsException) {
            event(new ThrottleHitEvent($request));
        }

        if ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();
            $wireConfig = new WireConfig('model404');

            /** @var MissingModelConfig $missingModelConfig */
            $missingModelConfig = $wireConfig->tripwires()[0];
            $needsProcessing = CheckOnlyExcept::needsProcessing($model, $missingModelConfig);
            if ($needsProcessing) {
                // attack found
                $violations = [$model];
                $triggerEventData = new TriggerEventData(
                    attackScore: $wireConfig->attackScore(),
                    violations: $violations,
                    triggerData: implode(',', $violations),
                    triggerRules: [],
                    trainingMode: $wireConfig->trainingMode(),
                    debugMode: $wireConfig->debugMode(),
                    comments: '',
                );
                event(new Model404FailedEvent($triggerEventData));

                BlockIfNeeded::run($request, $wireConfig->punish(), $wireConfig->trainingMode());
                // Response is not needed, consumer will handle the 404, this is just an additional inspector
            }

        }

        if ($e instanceof NotFoundHttpException) {
            $wireConfig = new WireConfig('page404');
            $value = $request->url();
            $config = $wireConfig->tripwires()[0];
            $needsProcessing = CheckOnlyExcept::needsProcessing($value, $config);

            if ($needsProcessing) {
                $violations = [$value];
                $triggerEventData = new TriggerEventData(
                    attackScore: $wireConfig->attackScore(),
                    violations: $violations,
                    triggerData: implode(',', $violations),
                    triggerRules: [],
                    trainingMode: $wireConfig->trainingMode(),
                    debugMode: $wireConfig->debugMode(),
                    comments: '',
                );
                event(new Page404FailedEvent($triggerEventData));

                BlockIfNeeded::run($request, $wireConfig->punish(), $wireConfig->trainingMode());
                // Response is not needed, consumer will handle the 404, this is just an additional inspector
            }
        }
    }
}
