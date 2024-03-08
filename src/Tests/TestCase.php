<?php

namespace Yormy\TripwireLaravel\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelRay\RayServiceProvider;

use Yormy\AssertLaravel\Helpers\AssertJsonMacros;
use Yormy\TripwireLaravel\TripwireServiceProvider;

abstract class TestCase extends BaseTestCase
{
    // disable after migration to inpect db during test
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        TestConfig::setup();

        $this->withoutExceptionHandling();

        TestRoutes::setup();

        AssertJsonMacros::register();
    }

    protected function getPackageProviders($app)
    {
        return [
            TripwireServiceProvider::class,
            RayServiceProvider::class,
        ];
    }

    /**
     * @psalm-return \Closure():'next'
     */
    public function getNextClosure(): \Closure
    {
        return function () {
            return 'next';
        };
    }
}
