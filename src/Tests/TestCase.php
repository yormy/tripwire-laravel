<?php

namespace Yormy\TripwireLaravel\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelRay\RayServiceProvider;
use Yormy\TripwireLaravel\TripwireServiceProvider;

abstract class TestCase extends BaseTestCase
{
    // disable after migration to inpect db during test
    // use RefreshDatabase;

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

    protected function setUpConfig(): void
    {
        config(['tripwire' => require __DIR__.'/../../config/tripwire.php']);
        config(['tripwire_wires' => require __DIR__.'/../../config/tripwire_wires.php']);
        config(['app.key' => 'base64:yNmpwO5YE6xwBz0enheYLBDslnbslodDqK1u+oE5CEE=']);
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
