<?php

namespace Yormy\TripwireLaravel;

use Illuminate\Support\ServiceProvider;
use Yormy\TripwireLaravel\Console\Commands\AnonymizeCommand;
use Yormy\TripwireLaravel\Console\Commands\DecryptDbCommand;
use Yormy\TripwireLaravel\Console\Commands\DecryptRecordCommand;
use Yormy\TripwireLaravel\Console\Commands\EncryptDbCommand;
use Yormy\TripwireLaravel\Console\Commands\GenerateEncryptionKeyCommand;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Agent;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Bot;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Geo;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Lfi;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Php;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Referer;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Rfi;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Session;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Sqli;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Swear;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;
use Yormy\TripwireLaravel\Observers\Events\Blocked\TripwireBlockedEvent;
use Yormy\TripwireLaravel\Observers\Listeners\Tripwires\LoginFailedWireListener;
use Yormy\TripwireLaravel\Observers\Listeners\NotifyUsers;
use Yormy\TripwireLaravel\ServiceProviders\EventServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Auth\Events\Failed as LoginFailed;

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
                EncryptDbCommand::class,
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
    }

    private function registerMiddlewareGroups($router)
    {
        foreach (config('tripwire.checker_groups', []) as $name => $items) {
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
