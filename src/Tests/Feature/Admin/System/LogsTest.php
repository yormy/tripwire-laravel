<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Management\System;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class LogsTest extends TestCase
{
    const ROUTE_INDEX = 'api.v1.admin.site.security.tripwire.logs.index';

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Logs_Index_HasItem(): void
    {
        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $count = $response->getDataCount(); // @phpstan-ignore-line

        $data = TripwireLog::factory()->create();
        usleep(100);

        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertSuccessful();
        $countAfter = $response->getDataCount(); // @phpstan-ignore-line

        $this->assertGreaterThan($count, $countAfter);
        $response->assertJsonDataArrayHasElement('xid', $data->xid); // @phpstan-ignore-line
    }
}
