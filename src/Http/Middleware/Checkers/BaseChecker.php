<?php

namespace Yormy\TripwireLaravel\Http\Middleware\Checkers;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\ConfigResponse;
use Yormy\TripwireLaravel\Repositories\BlockRepository;
use Yormy\TripwireLaravel\Repositories\LogRepository;
use Yormy\TripwireLaravel\Services\ResponseDeterminer;
use Yormy\TripwireLaravel\DataObjects\ConfigMiddleware;
use Yormy\TripwireLaravel\Services\UrlTester;

abstract class BaseChecker
{
    protected abstract function attackFound(array $violations): void;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware = strtolower((new \ReflectionClass($this))->getShortName());
        $this->user_id = auth()->id() ?: 0;

        $this->config = new ConfigMiddleware($this->middleware);
    }


    public function handle(Request $request, Closure $next)
    {
        if ($this->skip($request)) {
            return $next($request);
        }

        $patterns = $this->getPatterns();
        if ($this->isAttack($patterns)) {
            $configResponse = $this->getConfig($request, $this->middleware);
            $respond = new ResponseDeterminer($configResponse);
            if ($configResponse->asContinue() || $this->config->trainingMode) {
                return $next($request);
            }

            if ($request->wantsJson()) {
                return $respond->respondWithJson();
            }

            return $respond->respondWithHtml();
        }

        return $next($request);
    }

    protected function getAttackScore(): int
    {
        return $this->config->attackScore;
    }

    private function getConfig(Request $request, ?string $checker = null): ConfigResponse
    {
        $configName = 'trigger_response';
        if ($request->wantsJson()) {
            $configName .='.json';
        } else {
            $configName .='.html';
        }

        $generalResponse = config("tripwire.$configName");
        $triggerResponse = $generalResponse;
        if ($checker) {
            $checkerResponse = config('tripwire.middleware.' . $checker. '.'. "$configName", false);
            if (is_array($checkerResponse)) {
                $triggerResponse = $checkerResponse;
            }
        }

        return new ConfigResponse($request, $triggerResponse);
    }

    public function skip($request)
    {
        if ($this->config->isDisabled()) {
            return true;
        }

        if (UrlTester::skipUrl($request, config('tripwire.urls'))) {
            return true;
        }

        if ($this->config->isWhitelist($request)) {
            return true;
        }

        if ($this->config->skipMethod($request)) {
            return true;
        }

        if ($this->config->skipUrl($request)) {
            return true;
        }

        return false;
    }

    public function getPatterns()
    {
        return $this->config->patterns;
    }

    public function isAttack($patterns): bool
    {
        $violations = [];
        foreach ($patterns as $pattern) {
            $this->matchResults($pattern, $this->collectInputs(), $violations);
        }

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }

    private function removeItems(array $original, array $toRemove): array
    {
        $filtered = [];
        foreach ($original as $key => $value) {
            if (!in_array($key, $toRemove)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }
    /**
     * convert the inputs of the request to a string that can be scanned by the checkers
     * as we do not need to know what key it was or where it came from (input or header or cookie) we can
     * simply make 1 long string and check that with the presence of malicous input anywhere in that string
     */
    private function collectInputs(): string
    {
        $exceptInputs[] = 'remember';
        $exceptInputs = config('tripwire.ignore.inputs', []);
        $exceptCookies = config('tripwire.ignore.cookie', []);
        $exceptHeaders = config('tripwire.ignore.header', []);;
        $exceptHeaders[] = 'cookie';

        $inputsGlobalFilter = $this->removeItems($this->request->input(), $exceptInputs);

        $inputsLocalFilter = [];
        foreach ($inputsGlobalFilter as $key => $value) {
            if (!$this->config->skipInput($key)) {
                $inputsLocalFilter[$key] = $this->prepareInput($value);
            }
        }

        $cookies = $this->removeItems($this->request->cookie(), $exceptCookies);
        $headers = $this->removeItems($this->request->header(), $exceptHeaders);
        $fullUrl = $this->request->fullUrl();

        $scannableValues[] = $inputsLocalFilter;
        $scannableValues[] = $cookies;
        $scannableValues[] = $headers;
        $scannableValues[] = $fullUrl;

        return json_encode($scannableValues);
        return $scannableString;
    }

    public function matchResults($pattern, string $input, &$violations)
    {
        return preg_match($pattern, $input, $violations);
    }

    protected function matchAdditional($value): ?string
    {
        return null;
    }

    private function getSumViolationScore(int $punishableTimeframe, array $violations = []):\StdClass
    {
        $logRepository = new LogRepository();

        $ipAddressClass = config('tripwire.services.ip_address');
        $ipAddress = $ipAddressClass::get($this->request);

        $violationsByIp = $logRepository->queryViolationsByIp($punishableTimeframe, $ipAddress, $violations);
        $scoreByIp = $violationsByIp->get()->sum('event_score');

        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($this->request);
        $userType = $userClass::getType($this->request);
        $scoreByUser = 0;

        $violationsByUser = null;
        if ($userId) {
            $violationsByUser = $logRepository->queryViolationsByUser($punishableTimeframe, $userId, $userType, $violations);
            $scoreByUser = $violationsByUser->get()->sum('event_score');
        }

        $requestSourceClass = config('tripwire.services.request_source');
        $browserFingerprint =$requestSourceClass::getBrowserFingerprint();
        $scoreByBrowser = 0;
        $violationsByBrowser = null;
        if ($browserFingerprint) {
            $violationsByBrowser = $logRepository->queryViolationsByBrowser($punishableTimeframe, $browserFingerprint, $violations);
            $scoreByBrowser = $violationsByBrowser->get()->sum('event_score');
        }

        $result = new \StdClass();
        $result->maxScore = max($scoreByIp, $scoreByUser, $scoreByBrowser);
        $result->ipAddress = $ipAddress;
        $result->userId = $userId;
        $result->userType = $userType;
        $result->browserFingerprint = $browserFingerprint;
        $result->violationsByIp = $violationsByIp;
        $result->violationsByUser = $violationsByUser;
        $result->violationsByBrowser = $violationsByBrowser;

        return $result;
    }

    protected function blockIfNeeded()
    {
        $punishableTimeframe = (int)$this->config->punish->withinMinutes;

        $sum = $this->getSumViolationScore($punishableTimeframe);

        if ($sum->maxScore > (int)$this->config->punish->score) {
            $blockRepository = new BlockRepository();
            $blockItem = $blockRepository->add(
                penaltySeconds: (int)$this->config->punish->penaltySeconds,
                ipAddress: $sum->ipAddress,
                userId: $sum->userId,
                userType: $sum->userType,
                browserFingerprint: $sum->browserFingerprint,
                ignore: $this->config->trainingMode
            );

            $sum->violationsByIp->update(['tripwire_block_id' => $blockItem->id]);

            if (!$sum->violationsByUser) {
                $sum->violationsByUser->update(['tripwire_block_id' => $blockItem->id]);
            }

            if ($sum->violationsByBrowser) {
                $sum->violationsByBrowser->update(['tripwire_block_id' => $blockItem->id]);
            }

            return $blockItem;
        }

        return null;
    }

    protected function isGuardAttack(string $value, array $guards): bool
    {
        if ( !$value) {
            return false;
        }

        if ( empty($guards)) {
            return false;
        }

        $attackFound = false;

        if ( !empty($guards['allow']) && !in_array($value, $guards['allow'])) {
            $attackFound = true;
        }

        if (!empty($guards['block']) && in_array($value, $guards['block'])) {
            $attackFound = true;
        }

        if ($attackFound) {
            $this->attackFound([$value]);
            return true;
        }

        return false;
    }

    public function prepareInput($value)
    {
        return $value;
    }
}
