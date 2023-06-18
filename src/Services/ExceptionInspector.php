<?php

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Yormy\TripwireLaravel\DataObjects\Config\MissingModelConfig;
use Yormy\TripwireLaravel\DataObjects\Config\WireDetailsConfig;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\Model404FailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Tripwires\PageNotFoundEvent;
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
            $needsProcessing = self::needsProcessing($model);
            if ($needsProcessing) {
                // attack found
                $violations = [$model];
                $wireConfig = new WireConfig('model404');

                $triggerEventData = new TriggerEventData(
                    attackScore: $wireConfig->attackScore(),
                    violations: $violations,
                    triggerData: implode(',', $violations),
                    triggerRules: [],
                    trainingMode: $wireConfig->trainingMode(),
                    comments: '',
                );
                event(new Model404FailedEvent($triggerEventData));

                BlockIfNeeded::run($request, $wireConfig->punish(), $wireConfig->trainingMode()); // where to get punish and training from global or specific wire

                // Response is not needed, consumer will handle the 404, this is just an additional inspector
            }


        }

        if ($e instanceof NotFoundHttpException) {
            event(new PageNotFoundEvent($request));
        }
    }

    private static function needsProcessing(string $model)
    {
        $config = WireDetailsConfig::makeFromArray(config('tripwire_wires.model404'));

        /** @var MissingModelConfig $missingModelConfig */
        $missingModelConfig = $config->tripwires[0];

        if (self::isInclude($model, $missingModelConfig)) {
            return true;
        }

        if (!self::isExcluded($model, $missingModelConfig)) {
            return false;
        }

        return true;
    }


    private static function isInclude(string $model, MissingModelConfig $missingModelConfig): bool
    {
        if (empty($missingModelConfig->only)) {
            return true;
        }

        foreach ($missingModelConfig->only as $only) {
            if ($only === $model) {
                return true;
            }
        }

        return false;
    }

    private static function isExcluded(string $model, MissingModelConfig $missingModelConfig): bool
    {
        // Do not exclude if nothing specified
        if (empty($missingModelConfig->except)) {
            return false;
        }

        foreach ($missingModelConfig->only as $only) {
            if ($only === $model) {
                return true;
            }
        }

        return false;
    }

}
