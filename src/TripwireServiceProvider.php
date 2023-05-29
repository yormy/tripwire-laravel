<?php

namespace Yormy\TripwireLaravel;

use Illuminate\Auth\Events\Failed as LoginFailed;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Yormy\TripwireLaravel\Console\Commands\GenerateAccepts;
use Yormy\TripwireLaravel\Http\Middleware\Honeypot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Custom;
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
use Yormy\TripwireLaravel\Observers\Listeners\NotifyAdmin;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\LoginFailedWireListener;
use Yormy\TripwireLaravel\ServiceProviders\EventServiceProvider;
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

        $this->morphMaps();
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
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/tripwire-views'),
            ], 'views');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/tripwire'),
            ], 'translations');
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateAccepts::class,
            ]);
        }
    }

    public function registerMiddleware(Router $router): void
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
        $router->aliasMiddleware('tripwire.custom', Custom::class);

        $router->aliasMiddleware('tripwire.honeypotwire', Honeypot::class);
    }

    private function registerMiddlewareGroups(Router $router): void
    {
        foreach (config('tripwire.wire_groups', []) as $name => $items) {
            $router->middlewareGroup("tripwire.$name", $items);
        }
    }

    public function registerListeners(): void
    {
        $this->app['events']->listen(LoginFailed::class, LoginFailedWireListener::class);
        $this->app['events']->listen(TripwireBlockedEvent::class, NotifyAdmin::class);
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tripwire');
    }

    private function morphMaps()
    {
        $logModelpath = config('tripwire.models.log');
        $sections = explode('\\', $logModelpath);
        $LogModelName = end($sections);

        $blockModelpath = config('tripwire.models.block');
        $sections = explode('\\', $blockModelpath);
        $blockModelName = end($sections);

        Relation::enforceMorphMap([
            $LogModelName => $logModelpath,
            $blockModelName => $blockModelpath,
        ]);
    }
}
