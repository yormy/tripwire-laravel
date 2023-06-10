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
 //   use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

//        $this->setUpDatabase();
//
        $this->setUpConfig();
//
//        $this->artisan('vendor:publish', ['--tag' => 'firewall']);
//        $this->artisan('migrate:refresh', ['--database' => 'testbench']);
//          $this->artisan('migrate:fresh');
    }

    protected function getPackageProviders($app)
    {
        return [
            TripwireServiceProvider::class,
            RayServiceProvider::class,
        ];
    }

    private function resetDatabase()
    {
        Schema::dropAllTables();

        $this->migrateDependent();

        $this->artisan('migrate');

        $this->migrateDependentAfter();

        $this->runSeeders();

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            //$this->artisan('migrate:rollback'); // prevent foreign key problems

            RefreshDatabaseState::$migrated = false;
        });
    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//    }
//
//    protected function getPackageProviders($app)
//    {
//        return [
//            Provider::class,
//        ];
//    }
//
// dit moet toch in xml kunnen ?
//    protected function setUpDatabase()
//    {
//        config(['database.default' => 'testbench']);
//
//        config(['database.connections.testbench' => [
//                'driver'   => 'sqlite',
//                'database' => ':memory:',
//                'prefix'   => '',
//            ],
//        ]);
//    }
//
    protected function setUpConfig()
    {
        $this->artisan('ray:publish-config');
        config(['tripwire' => require __DIR__ . '/../../config/tripwire.php']);
        config(['tripwire_wires' => require __DIR__ . '/../../config/tripwire_wires.php']);
//
//        config(['firewall.notifications.mail.enabled' => false]);
//        config(['firewall.middleware.ip.methods' => ['all']]);
//        config(['firewall.middleware.lfi.methods' => ['all']]);
//        config(['firewall.middleware.rfi.methods' => ['all']]);
//        config(['firewall.middleware.sqli.methods' => ['all']]);
        config(['tripwire_wires.xss.methods' => ['all']]);

        config(['app.key' => 'base64:yNmpwO5YE6xwBz0enheYLBDslnbslodDqK1u+oE5CEE=']);
//
//        config([
//            'ray.enable' => true,
//            'ray.host' => 'buggregator',
//            'ray.port' => 8000
//        ]);
    }
    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}
