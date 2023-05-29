<?php

namespace Yormy\TripwireLaravel\Services;

use Mexion\BedrockCore\DataObjects\Responses\CoreError;
use Mexion\BedrockCore\Exceptions\ExceptionResponse;
use Mexion\BedrockUsers\Helpers\CheckHoneypot;
use function Mexion\BedrockCore\Services\Security\dd;

class Honeypot
{
//    public static function check($request, array $honeypots): void
//    {
//        foreach ($honeypots as $honeypotName) {
//            $honeypotContent = $request->get($honeypotName);
//            if ('' !== $honeypotContent && null !== $honeypotContent) {
//                //event(new TarpitTriggerEvent('Honeypot', new TarpitTypeHackAttempt())); // trigger tripwire
//
//                dd('honeypot trigger present'. $honeypotName);
//                throw new ExceptionResponse(CoreError::HONEYPOT_TRIGGERED);
//            }
//        }
//    }

    public static function checkFalseValues($request, array $honeypots = []): void
    {
        foreach ($honeypots as $honeypotName) {
            $honeypotContent = $request->get($honeypotName);
            if ($honeypotContent) {
                //event(new TarpitTriggerEvent('Honeypot', new TarpitTypeHackAttempt())); // trigger tripwire

                dd('honeypot trigger not false'. $honeypotName);
                throw new ExceptionResponse(CoreError::HONEYPOT_TRIGGERED);
            }
        }
    }
}
