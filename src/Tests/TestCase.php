<?php

namespace Yormy\TripwireLaravel\Tests;

use Akaunting\Firewall\Provider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

//        $this->setUpDatabase();
//
        $this->setUpConfig();
//
//        $this->artisan('vendor:publish', ['--tag' => 'firewall']);
//        $this->artisan('migrate:refresh', ['--database' => 'testbench']);
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
        config(['tripwire' => require __DIR__ . '/../../config/tripwire.php']);
        config(['tripwire_wires' => require __DIR__ . '/../../config/tripwire_wires.php']);
//
//        config(['firewall.notifications.mail.enabled' => false]);
//        config(['firewall.middleware.ip.methods' => ['all']]);
//        config(['firewall.middleware.lfi.methods' => ['all']]);
//        config(['firewall.middleware.rfi.methods' => ['all']]);
//        config(['firewall.middleware.sqli.methods' => ['all']]);
//        config(['firewall.middleware.xss.methods' => ['all']]);
    }

    public function getNextClosure()
    {
        return function () {
            return 'next';
        };
    }
}
