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
     */
    public function ResetKey_Get_Success(): void
    {
        $response = $this->json('GET', route(static::ROUTE_RESET_KEY));
        $response->assertSuccessful();

        $data = json_decode($response->getContent());
        $this->assertStringContainsString('reset?expires', $data->url);

        // reset works if enabled
        // not if disabled
        // not if improper key?
        // not if expired
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function ResetKeyDisabled_Get_Failed(): void
    {
        config(['tripwire.reset.enabled' => false]);
        $response = $this->json('GET', route(static::ROUTE_RESET_KEY));
        $response->assertNotFound();
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function ResetForUser(): void
    {
        /*
         * Test if user logs are unblocked when reset key is used
         * Test if reset key is the right one
         * Test if reset is enabled or fail
         */
        $this->markTestSkipped('Users untested');
    }
}
