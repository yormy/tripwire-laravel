<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
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
    public const CONFIG_FILE = __DIR__.'/../config/tripwire.php';

    public const CONFIG_WIRE_FILE = __DIR__.'/../config/tripwire_wires.php';

    public const CONFIG_IDE_HELPER_FILE = __DIR__.'/../config/ide-helper.php';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot(Router $router): void
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
    public function register(): void
    {
        $this->mergeConfigFrom(self::CONFIG_FILE, 'tripwire');
        $this->mergeConfigFrom(self::CONFIG_WIRE_FILE, 'tripwire_wires');
        $this->mergeConfigFrom(static::CONFIG_IDE_HELPER_FILE, 'ide-helper');

        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(IdeHelperServiceProvider::class);
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

    public function registerListeners(): void
    {
        $this->app['events']->listen(LoginFailed::class, LoginFailedWireListener::class); // @phpstan-ignore-line
        $this->app['events']->listen(TripwireBlockedEvent::class, NotifyAdmin::class); // @phpstan-ignore-line
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tripwire');
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

    private function registerMiddlewareGroups(Router $router): void
    {
        $groups = (array) config('tripwire.wire_groups', []);
        foreach ($groups as $name => $items) {
            $items = (array) $items;
            $router->middlewareGroup("tripwire.{$name}", $items);
        }
    }

    private function morphMaps(): void
    {
        $logModelpath = strval(config('tripwire.models.log')); //@phpstan-ignore-line
        $sections = explode('\\', $logModelpath);
        $LogModelName = end($sections);

        $blockModelpath = strval(config('tripwire.models.block')); //@phpstan-ignore-line
        $sections = explode('\\', $blockModelpath);
        $blockModelName = end($sections);

        Relation::enforceMorphMap([
            $LogModelName => $logModelpath,
            $blockModelName => $blockModelpath,
        ]);
    }
}
