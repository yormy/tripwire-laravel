<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\DataObjects\WireConfig;
use Yormy\TripwireLaravel\Observers\Events\Failed\HoneypotFailedEvent;
use Yormy\TripwireLaravel\Services\BlockIfNeeded;
use Yormy\TripwireLaravel\Services\Honeypot;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;

/**
 * Goal:
 * Trigger when hackers try to edit the request in a way to guess hidden parameters.
 *
 * Effect:
 * When a honeypot is filled or changed the application will immediately notice malicious intent
 * No real user would do this
 */
class HoneypotsWire
{
    /**
     * @return mixed
     *
     * @throws \Mexion\BedrockCore\Exceptions\ExceptionResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $wireConfig = new WireConfig('honeypots');

        $honeypotsMustBeFalseOrMissing= $wireConfig->tripwires();

        $violations = Honeypot::checkFalseValues($request, $honeypotsMustBeFalseOrMissing);

        if (!empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $wireConfig->attackScore(),
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $wireConfig->trainingMode(),
                comments: '',
            );

            $this->attackFound($request, $triggerEventData, $wireConfig);

            if ($request->wantsJson()) {
                $config = JsonResponseConfig::makeFromArray(config('tripwire.trigger_response.json'));
                $respond = new ResponseDeterminer($config, $request->url());
                return $respond->respondWithJson();
            }

            $config = HtmlResponseConfig::makeFromArray(config('tripwire.trigger_response.html'));
            $respond = new ResponseDeterminer($config, $request->url());
            return $respond->respondWithHtml();
        }

        $this->cleanup($request, $wireConfig);

        return $next($request);
    }


    protected function attackFound(Request $request, TriggerEventData $triggerEventData, $config): void
    {
        event(new HoneypotFailedEvent($triggerEventData));

        BlockIfNeeded::run($request, $config->punish(), $config->trainingMode());
    }

    protected function cleanup(Request $request, $wireConfig): void
    {
        foreach ($wireConfig->tripwires() as $field) {
            $request->request->remove($field);
        }
    }
}
