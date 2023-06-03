<?php

namespace Yormy\TripwireLaravel;

use Illuminate\Support\ServiceProvider;
use Yormy\TripwireLaravel\Console\Commands\AnonymizeCommand;
use Yormy\TripwireLaravel\Console\Commands\DecryptDbCommand;
use Yormy\TripwireLaravel\Console\Commands\DecryptRecordCommand;
use Yormy\TripwireLaravel\Console\Commands\EncryptDbCommand;
use Yormy\TripwireLaravel\Console\Commands\GenerateEncryptionKeyCommand;
use Yormy\TripwireLaravel\Http\Middleware\Swear;
use Yormy\TripwireLaravel\Observers\Listeners\LoginFailedListener;
use Yormy\TripwireLaravel\ServiceProviders\EventServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Auth\Events\Failed as LoginFailed;

class TripwireServiceProvider extends ServiceProvider
{
    const CONFIG_FILE = __DIR__.'/../config/tripwire.php';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot(Router $router)
    {
        $this->publish();

        $this->registerCommands();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->registerMiddleware($router);

        $this->registerListeners();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tripwire-laravel');
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function register()
    {
        $this->mergeConfigFrom(static::CONFIG_FILE, 'tripwire');

        $this->app->register(EventServiceProvider::class);
    }

    private function publish(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_FILE => config_path('tripwire.php'),
            ], 'tripwire-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'tripwire-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/tripwire-views'),
            ]);
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
//        $router->middlewareGroup('firewall.all', config('firewall.all_middleware'));
//        $router->aliasMiddleware('firewall.agent', 'Akaunting\Firewall\Middleware\Agent');
//        $router->aliasMiddleware('firewall.bot', 'Akaunting\Firewall\Middleware\Bot');
//        $router->aliasMiddleware('firewall.ip', 'Akaunting\Firewall\Middleware\Ip');
//        $router->aliasMiddleware('firewall.geo', 'Akaunting\Firewall\Middleware\Geo');
//        $router->aliasMiddleware('firewall.lfi', 'Akaunting\Firewall\Middleware\Lfi');
//        $router->aliasMiddleware('firewall.php', 'Akaunting\Firewall\Middleware\Php');
//        $router->aliasMiddleware('firewall.referrer', 'Akaunting\Firewall\Middleware\Referrer');
//        $router->aliasMiddleware('firewall.rfi', 'Akaunting\Firewall\Middleware\Rfi');
//        $router->aliasMiddleware('firewall.session', 'Akaunting\Firewall\Middleware\Session');
//        $router->aliasMiddleware('firewall.sqli', 'Akaunting\Firewall\Middleware\Sqli');
        $router->aliasMiddleware('firewall.swear', Swear::class);
//        $router->aliasMiddleware('firewall.url', 'Akaunting\Firewall\Middleware\Url');
//        $router->aliasMiddleware('firewall.whitelist', 'Akaunting\Firewall\Middleware\Whitelist');
//        $router->aliasMiddleware('firewall.xss', 'Akaunting\Firewall\Middleware\Xss');
    }

    public function registerListeners()
    {
        $this->app['events']->listen(LoginFailed::class, LoginFailedListener::class);
    }
}
