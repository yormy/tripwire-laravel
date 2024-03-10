<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Management\System;

use Yormy\AssertLaravel\Traits\RouteHelperTrait;
use Yormy\TripwireLaravel\Tests\TestCase;

class ResetTest extends TestCase
{
    use RouteHelperTrait;
    const ROUTE_RESET_KEY = 'api.v1.admin.members.account.tripwire.reset-key';

    /**
     * @test
     *
     * @group tripwire-api
     * @group xxx
     */
    public function ResetKey_Get_Success(): void
    {
        $response = $this->json('GET', route(static::ROUTE_RESET_KEY));
        $response->assertSuccessful();

        $data = json_decode($response->getContent());
        $this->assertStringContainsString('reset?expires', $data->url );

        // reset works if enabled
        // not if disabled
        // not if improper key?
        // not if expired
    }

    /**
     * @test
     *
     * @group tripwire-api
     * @group xxx
     */
    public function ResetKeyDisabled_Get_Failed(): void
    {
        config(['tripwire.reset.enabled' => false]);
        $response = $this->json('GET', route(static::ROUTE_RESET_KEY));
        $response->assertNotFound();
    }
}
