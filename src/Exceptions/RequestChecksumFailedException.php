<?php

namespace Yormy\TripwireLaravel\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Mexion\BedrockCore\DataObjects\Security\Tarpit\TarpitTypeHackAttempt;
use Mexion\BedrockCore\Observers\Events\TarpitTriggerEvent;
use Yormy\TripwireLaravel\Actions\BlockIpAction;
use Yormy\TripwireLaravel\Observers\Events\RequestChecksumFailedEvent;
use Yormy\TripwireLaravel\Observers\Events\TestFailedEvent;

class RequestChecksumFailedException extends BaseException
{
    protected function dispatchEvents(Request $request)
    {
        event(new TestFailedEvent());
//        BlockIpAction::exec();

//        event(new TarpitTriggerEvent(
//            'HACK_ATTEMPT_EXCEPTION',
//            new TarpitTypeHackAttempt(),
//            $this->ipAddress,
//            '',
//            $this->details,
//            $this->userId
//        ));
    }

    protected function renderJson(Request $request)
    {
        //https://dev.to/jackmiras/laravels-exceptions-part-2-custom-exceptions-1367
        $status = 400;
        $error = "Something is wrong";
        $help = "Contact the sales team to verify";

        return response(["error" => $error, "help" => $help], $status);
    }

    protected function renderHtml(Request $request)
    {
        return "dddd";
    }
}
