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
use Yormy\TripwireLaravel\Services\Routes;

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
            if ($configResponse->asContinue()) {
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

        if (Routes::skipRoute($request, config('tripwire.whitelist.routes'))) {
            return true;
        }

        if ($this->config->isWhitelist($request)) {
            return true;
        }

        if ($this->config->skipMethod($request)) {
            return true;
        }

        if ($this->config->skipRoute($request)) {
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
            $this->matchResults($pattern, $this->request->input(), $violations);
        }

        if (!empty($violations))  {
            $this->attackFound($violations);
        }

        return !empty($violations);
    }


    public function matchResults($pattern, $input, &$violations)
    {
        $result = false;
        if ( !is_array($input) && !is_string($input)) {
            return false;
        }

        if ( !is_array($input)) {
            $input = $this->prepareInput($input);
            return preg_match($pattern, $input, $matches);
        }

        foreach ($input as $key => $value) {
            if (empty($value)) {
                continue;
            }

            if (is_array($value)) {
                if ($result = $this->matchResults($pattern, $value, $matches)) {
                    $violations[] = $matches[0];
                }
            }

            if ($this->config->skipInput($key)) {
                return true;
            }

            $value = $this->prepareInput($value);

            if ( $result = preg_match($pattern, $value, $matches)) {
                $violations[] = $matches[0];
            }

            if ($match = $this->matchAdditional($value))
            {
                $violations[] = $matches;
            }
        }

        return $result;
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
