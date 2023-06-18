<?php

namespace Yormy\TripwireLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\DataObjects\ConfigBuilder;
use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Yormy\TripwireLaravel\DataObjects\TriggerEventData;
use Yormy\TripwireLaravel\Jobs\AddBlockJob;
use Yormy\TripwireLaravel\Observers\Events\Failed\HoneypotFailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Failed\TextFailedEvent;
use Yormy\TripwireLaravel\Observers\Events\Failed\XssFailedEvent;
use Yormy\TripwireLaravel\Services\Honeypot;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\Traits\TripwireHelpers;

/**
 * Goal:
 * Trigger when hackers try to edit the request in a way to guess hidden parameters.
 *
 * Effect:
 * When a honeypot is filled or changed the application will immediately notice malicious intent
 * No real user would do this
 */
class HoneypotsCheck
{
    /**
     * @return mixed
     *
     * @throws \Mexion\BedrockCore\Exceptions\ExceptionResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $honeypotsMustBeFalseOrMissing = (array)config('tripwire.honeypots.must_be_missing_or_false');
        $violations = Honeypot::checkFalseValues($request, $honeypotsMustBeFalseOrMissing);

        $config = ConfigBuilder::fromArray(config('tripwire'));

        if (!empty($violations)) {
            $triggerEventData = new TriggerEventData(
                attackScore: $config->honeypots->attackScore,
                violations: $violations,
                triggerData: implode(',', $violations),
                triggerRules: [],
                trainingMode: $config->trainingMode,
                comments: '',
            );

            $this->attackFound($request, $triggerEventData, $config);

            if ($request->wantsJson()) {
                $config = JsonResponseConfig::makeFromArray(config('tripwire.trigger_response.json'));
                $respond = new ResponseDeterminer($config, $request->url());
                return $respond->respondWithJson();
            }

            $config = HtmlResponseConfig::makeFromArray(config('tripwire.trigger_response.html'));
            $respond = new ResponseDeterminer($config, $request->url());
            return $respond->respondWithHtml();
        }

        $this->cleanup($request);

        return $next($request);
    }


    protected function attackFound(Request $request, TriggerEventData $triggerEventData, $config): void
    {
        event(new HoneypotFailedEvent($triggerEventData));

        $this->blockIfNeeded($request, $config);
    }

    // todo : normalize with other blockifneeded function
    protected function blockIfNeeded(Request $request, $config)
    {
        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($this->request ?? null);
        $userClass = config('tripwire.services.user');

        $userId = 0;
        $userType = '';
        if ($this->request ?? false) {
            $userId = $userClass::getId($request);
            $userType = $userClass::getType($request);
        }

        AddBlockJob::dispatch(
            ipAddress: $ipAddress,
            userId: $userId,
            userType: $userType,
            withinMinutes: $config->punish->withinMinutes,
            thresholdScore: $config->punish->score,
            penaltySeconds: $config->punish->penaltySeconds,
            trainingMode: $config->trainingMode,
        );
    }

    protected function cleanup(Request $request): void
    {
        $honeypotsMustBeFalseOrMissing = config('tripwire.honeypots.must_be_missing_or_false');
        foreach ($honeypotsMustBeFalseOrMissing as $field) {
            $request->request->remove($field);
        }
    }
}
