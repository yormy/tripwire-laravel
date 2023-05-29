<?php

namespace Yormy\TripwireLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use Yormy\TripwireLaravel\Observers\TripwireSubscriber;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        TripwireSubscriber::class,
    ];
}
