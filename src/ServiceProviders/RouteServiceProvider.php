<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Yormy\TripwireLaravel\Routes\AdminRoutes;
use Yormy\TripwireLaravel\Routes\GuestRoutes;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->map();
    }

    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        AdminRoutes::register();
        GuestRoutes::register();
    }

    protected function mapApiRoutes(): void
    {
    }
}
