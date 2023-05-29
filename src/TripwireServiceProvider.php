<?php

namespace Yormy\TripwireLaravel;

use Illuminate\Support\ServiceProvider;
use Yormy\TripwireLaravel\Console\Commands\AnonymizeCommand;
use Yormy\TripwireLaravel\Console\Commands\DecryptDbCommand;
use Yormy\TripwireLaravel\Console\Commands\DecryptRecordCommand;
use Yormy\TripwireLaravel\Console\Commands\EncryptDbCommand;
use Yormy\TripwireLaravel\Console\Commands\GenerateEncryptionKeyCommand;
use Yormy\TripwireLaravel\ServiceProviders\EventServiceProvider;

class TripwireServiceProvider extends ServiceProvider
{
    const CONFIG_FILE = __DIR__.'/../config/tripwire.php';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot()
    {
        $this->publish();

        $this->registerCommands();
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
            ], 'config');
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                EncryptDbCommand::class,
                GenerateEncryptionKeyCommand::class,
                DecryptDbCommand::class,
                DecryptRecordCommand::class
            ]);
        }
    }
}
