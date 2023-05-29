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
use Yormy\TripwireLaravel\Observers\Events\LoggableEvent;
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
        //https://owenconti.com/posts/how-to-handle-multiple-events-with-a-single-listener-in-laravel
        // all events are cought by single listener
        $events->listen(
            LoggableEvent::class,
            LogEvent::class
        );

    }
}
