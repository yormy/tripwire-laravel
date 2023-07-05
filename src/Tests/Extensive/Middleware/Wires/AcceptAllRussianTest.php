<?php

namespace Yormy\TripwireLaravel\Tests\Extensive\Middleware\Wires;

class AcceptAllRussianTest extends BaseAcceptAll
{
    protected string $acceptsDataFile = './src/Tests/Dataproviders/AcceptsData-ru_RU.txt';

    /**
     * @test
     *
     * @group tripwire-accept-all
     *
     * @dataProvider accepts
     */
    public function should_accept($accept): void
    {
        $this->setConfig();
        $this->triggerTripwire($accept);
    }
}
