<?php

namespace Yormy\TripwireLaravel;

use Illuminate\Support\ServiceProvider;
use Yormy\TripwireLaravel\Console\Commands\TestConfigCommand;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Geo;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Lfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Php;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Referer;
use Yormy\TripwireLaravel\Http\Middleware\Wires\RequestSize;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Rfi;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Session;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Sqli;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Swear;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Xss;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\LoginFailedWireListener;
use Yormy\TripwireLaravel\Observers\Listeners\NotifyUsers;
use Yormy\TripwireLaravel\ServiceProviders\EventServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Auth\Events\Failed as LoginFailed;
use Yormy\TripwireLaravel\ServiceProviders\RouteServiceProvider;

class TripwireServiceProvider extends ServiceProvider
{
    const CONFIG_FILE = __DIR__.'/../config/tripwire.php';
    const CONFIG_WIRE_FILE = __DIR__.'/../config/tripwire_wires.php';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot(Router $router)
    {
        $this->publish();

        $this->registerCommands();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->registerMiddleware($router);
        $this->registerMiddlewareGroups($router);

        $this->registerListeners();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tripwire-laravel');

        $this->registerTranslations();
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function register()
    {
        $this->mergeConfigFrom(static::CONFIG_FILE, 'tripwire');
        $this->mergeConfigFrom(static::CONFIG_WIRE_FILE, 'tripwire_wires');

        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    private function publish(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_FILE => config_path('tripwire.php'),
                self::CONFIG_WIRE_FILE => config_path('tripwire_wires.php'),
            ], 'tripwire-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'tripwire-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/tripwire-views'),
            ]);

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/tripwire'),
            ], 'translations');
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
            ]);
        }
    }

    public function registerMiddleware($router)
    {
        $router->aliasMiddleware('tripwire.agent', Agent::class);
        $router->aliasMiddleware('tripwire.bot', Bot::class);
        $router->aliasMiddleware('tripwire.geo', Geo::class);
        $router->aliasMiddleware('tripwire.lfi', Lfi::class);
        $router->aliasMiddleware('tripwire.php', Php::class);
        $router->aliasMiddleware('tripwire.referer', Referer::class);
        $router->aliasMiddleware('tripwire.rfi', Rfi::class);
        $router->aliasMiddleware('tripwire.session', Session::class);
        $router->aliasMiddleware('tripwire.sqli', Sqli::class);
        $router->aliasMiddleware('tripwire.swear', Swear::class);
        $router->aliasMiddleware('tripwire.text', Text::class);
        $router->aliasMiddleware('tripwire.xss', Xss::class);
        $router->aliasMiddleware('tripwire.request_size', RequestSize::class);
    }

    private function registerMiddlewareGroups($router)
    {
        foreach (config('tripwire.wire_groups', []) as $name => $items) {
            $router->middlewareGroup("tripwire.$name", $items);
        }
    }

    public function registerListeners()
    {
        $this->app['events']->listen(LoginFailed::class, LoginFailedWireListener::class);
        $this->app['events']->listen(TripwireBlockedEvent::class, NotifyUsers::class);
    }

    public function registerTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tripwire');
    }
}
