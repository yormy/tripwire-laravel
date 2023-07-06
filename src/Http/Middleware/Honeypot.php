<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\HoneypotFailedEvent;
use Yormy\TripwireLaravel\Services\BlockIfNeeded;
use Yormy\TripwireLaravel\Services\HoneypotService;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;

/**
 * Goal:
 * Trigger when hackers try to edit the request in a way to guess hidden parameters.
 *
 * Effect:
 * When a honeypot is filled or changed the application will immediately notice malicious intent
 * No real user would do this
 */
class Honeypot
{
    public const NAME = 'honeypot';

    /**
     * @return mixed
     *
     * @throws \Mexion\BedrockCore\Exceptions\ExceptionResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $wireConfig = new WireConfig(self::NAME);

        $honeypotsMustBeFalseOrMissing = $wireConfig->tripwires();

        $violations = HoneypotService::checkFalseValues($request, $honeypotsMustBeFalseOrMissing);

        if (! empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $wireConfig->attackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $wireConfig->trainingMode(),
                debugMode: $wireConfig->debugMode(),
                comments: '',
            );

            $this->attackFound($request, $triggerEventData, $wireConfig);

            if ($request->wantsJson()) {
                $config = JsonResponseConfig::makeFromArray(config('tripwire.reject_response.json'));
                $respond = new ResponseDeterminer($config, $request->url());

                return $respond->respondWithJson();
            }

            $config = HtmlResponseConfig::makeFromArray(config('tripwire.reject_response.html'));
            $respond = new ResponseDeterminer($config, $request->url());

            return $respond->respondWithHtml();
        }

        $this->cleanup($request, $wireConfig);

        return $next($request);
    }

    protected function attackFound(Request $request, TriggerEventData $triggerEventData, WireConfig $config): void
    {
        event(new HoneypotFailedEvent($triggerEventData));

        BlockIfNeeded::run($request, $config->punish(), $config->trainingMode());
    }

    protected function cleanup(Request $request, WireConfig $wireConfig): void
    {
        foreach ($wireConfig->tripwires() as $field) {
            $request->request->remove($field);
        }
    }
}
