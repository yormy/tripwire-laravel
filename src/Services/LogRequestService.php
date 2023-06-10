<?php

namespace Yormy\TripwireLaravel\Services;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;

class LogRequestService
{
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


    private static function addRequest(Request $request, array $data): array
    {
        $data['url'] = $request->fullUrl();
        $data['method'] = $request->method();

        $logReferer = $request->headers->get('referer');
        if ($logReferer) {
            $logReferer = substr($request->headers->get('referer'), 0, config('tripwire.log.max_referer_size'));
        }
        $data['referer'] = $logReferer;

        $logHeader = $request->header();
        if ($logHeader) {
            $logHeader = substr(json_encode($request->header()), 0, config('tripwire.log.max_header_size'));
        }
        $data['header'] = $logHeader;
        $data['request'] = self::getRequestString($request);
        $data['request_fingerprint'] = self::fingerprint($request);

        return $data;
    }

    private static function addUser(Request $request, array $data): array
    {
        $userClass = config('tripwire.services.user');
        $userId = $userClass::getId($request);
        $userType = $userClass::getType($request);

        $data['user_id'] = $userId ?? null;
        $data['user_type'] = $userType  ?? null;

        return $data;
    }

    private static function addUserAgent(array $data): array
    {
        $requestSourceClass = config('tripwire.services.request_source');

        $userAgent = substr(json_encode($requestSourceClass::getUserAgent()), 0, 190);
        $data['user_agent'] = $userAgent;
        $data['robot_crawler'] = $requestSourceClass::getRobot();
        $data['browser_fingerprint'] = $requestSourceClass::getBrowserFingerprint();

        return $data;
    }

    private static function fingerprint(Request $request)
    {
        return HashService::create(json_encode([
            $request->url(),
            $request->method(),
            $request->ips(),
            $request->header(),
            $request->all()
        ]));
    }

    private static function getRequestString(Request $request): string
    {
        $inputs = $request->all();
        foreach(config('tripwire.log.remove', []) as $field) {
            unset($inputs[$field]);
        }

        $logRequest = json_encode($inputs);
        if ($logRequest) {
            $logRequest = substr($logRequest, 0, config('tripwire.log.max_request_size'));
        }

        return $logRequest;
    }
}