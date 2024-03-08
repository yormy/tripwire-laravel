<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Management;

use Illuminate\Support\Carbon;
use Yormy\AssertLaravel\Traits\RouteHelperTrait;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;
use Yormy\TripwireLaravel\Tests\TestCase;

class BlocksTest extends TestCase
{
    use RouteHelperTrait;

    const ROUTE_INDEX = 'api.v1.admin.site.security.tripwire.blocks.index';

    /**
     * @test
     *
     * @group tripwire-api
     * @group xxx
     */
    public function Blocks_Index_HasItem(): void
    {
        $blockedIp = '0.0.0';
        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertSuccessful();
        $response->assertJsonDataArrayNotHasElement('blocked_ip', $blockedIp);

        TripwireBlock::factory(1)->create(['blocked_ip' => $blockedIp]);
        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayHasElement('blocked_ip', $blockedIp);
    }

}
