<?php

namespace Yormy\TripwireLaravel\Observers;

use Mexion\BedrockCore\Observers\Listeners\MailTracker\EmailClickedListener;
use Mexion\BedrockCore\Observers\Listeners\MailTracker\EmailComplaintListener;
use Mexion\BedrockCore\Observers\Listeners\MailTracker\EmailDeliveredListener;
use Mexion\BedrockCore\Observers\Listeners\MailTracker\EmailPermanentBouncedListener;
use Mexion\BedrockCore\Observers\Listeners\MailTracker\EmailSentListener;
use Mexion\BedrockCore\Observers\Listeners\MailTracker\EmailViewedListener;
use jdavidbakr\MailTracker\Events\ComplaintMessageEvent;
use jdavidbakr\MailTracker\Events\EmailDeliveredEvent;
use jdavidbakr\MailTracker\Events\EmailSentEvent;
use jdavidbakr\MailTracker\Events\LinkClickedEvent;
use jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent;
use jdavidbakr\MailTracker\Events\ViewEmailEvent;
use Illuminate\Events\Dispatcher;
use Yormy\TripwireLaravel\Observers\Events\RequestChecksumFailedEvent;
use Yormy\TripwireLaravel\Observers\Listeners\LogEvent;

class TripwireSubscriber
{
    /**
     * Binding of SettingsChanged Events
     *
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RequestChecksumFailedEvent::class,
            LogEvent::class
        );

    }
}
