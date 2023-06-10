<?php

namespace Yormy\TripwireLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Routes\AdminRoutes;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->map();

    }

    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        AdminRoutes::register();
    }

    protected function mapApiRoutes()
    {
    }
}
