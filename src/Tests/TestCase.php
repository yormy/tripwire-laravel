<?php

namespace Yormy\TripwireLaravel\Tests;

use Akaunting\Firewall\Provider;
use Clarkeash\Doorman\Providers\DoormanServiceProvider;
use Igaster\LaravelTheme\themeServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Schema;
use Mexion\BedrockUsers\BedrockUsersServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelSettings\LaravelSettingsServiceProvider;
use Spatie\LaravelRay\RayServiceProvider;
use Yormy\TripwireLaravel\TripwireServiceProvider;

abstract class TestCase extends BaseTestCase
{
    // disable after migration to inpect db during test
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpConfig();
    }

    protected function getPackageProviders($app)
    {
        return [
            TripwireServiceProvider::class,
            RayServiceProvider::class,
        ];
    }

    protected function setUpConfig()
    {
        config(['tripwire' => require __DIR__ . '/../../config/tripwire.php']);
        config(['tripwire_wires' => require __DIR__ . '/../../config/tripwire_wires.php']);
        config(['app.key' => 'base64:yNmpwO5YE6xwBz0enheYLBDslnbslodDqK1u+oE5CEE=']);
    }

    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}
