<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

use Illuminate\Http\Request;

class LogRequestService
{
    /**
     * @return array<string>
     */
    public static function getMeta(Request $request): array
    {
        $data = [];
        $data['ip'] = $request->ip();
        $data['ips'] = json_encode($request->ips());
        $data = static::addRequest($request, $data);
        $data = static::addUser($request, $data);
        $data = static::addUserAgent($data);

        return $data;
    }

    /**
     * @param  array<string, string|false|null>  $data
     *
     * @return array<string,array|bool|string|null>
     */
    protected static function addRequest(Request $request, array $data): array
    {
        $data['url'] = self::truncateValue($request->fullUrl());
        $data['method'] = $request->method();

        $logReferer = $request->headers->get('referer');
        if ($logReferer) {
            $logReferer = substr($request->headers->get('referer'), 0, config('tripwire.log.max_referer_size')); // @phpstan-ignore-line
        }
        $data['referer'] = $logReferer;

        $logHeader = $request->header();
        if ($logHeader) {
            $header = FieldMasker::run($request->header());
            $logHeader = substr(json_encode($header), 0, config('tripwire.log.max_header_size')); // @phpstan-ignore-line
        }
        $data['header'] = $logHeader;
        $data['request'] = self::getRequestString($request);
        $data['request_fingerprint'] = self::fingerprint($request);

        return $data;
    }

    /**
     * @param  array<string,array|bool|string|null>  $data
     *
     * @return array<string>
     */
    protected static function addUser(Request $request, array $data): array
    {
        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request); // @phpstan-ignore-line
        $userType = $userClass::getType($request); // @phpstan-ignore-line

        $data['user_id'] = $userId ?? null;
        $data['user_type'] = $userType ?? null;

        return $data;
    }

    /**
     * @param  array<string>  $data
     *
     * @return array<string>
     */
    protected static function addUserAgent(array $data): array
    {
        $requestSourceClass = config('tripwire.services.request_source');

        $userAgent = substr(json_encode($requestSourceClass::getUserAgent()), 0, 190); // @phpstan-ignore-line
        $data['user_agent'] = $userAgent;
        $data['robot_crawler'] = $requestSourceClass::getRobot(); // @phpstan-ignore-line
        $data['browser_fingerprint'] = $requestSourceClass::getBrowserFingerprint(); // @phpstan-ignore-line

        return $data;
    }

    private static function truncateValue(string $value, int $max = 150): string
    {
        return substr($value, 0, $max);
    }

    private static function fingerprint(Request $request): string
    {
        return HashService::create(json_encode([ // @phpstan-ignore-line
            $request->url(),
            $request->method(),
            $request->ips(),
            $request->header(),
            $request->all(),
        ]));
    }

    private static function getRequestString(Request $request): string
    {
        $inputs = $request->all();

        $inputs = FieldMasker::run($inputs);

        /** @var string $logRequest */
        $logRequest = json_encode($inputs);
        if ($logRequest) {
            $logRequest = substr($logRequest, 0, config('tripwire.log.max_request_size')); // @phpstan-ignore-line
        }

        return $logRequest;
    }
}
