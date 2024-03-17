<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Yormy\TripwireLaravel\Observers\TripwireSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<string> $subscribe
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint
     */
    protected $subscribe = [
        TripwireSubscriber::class,
    ];
}
