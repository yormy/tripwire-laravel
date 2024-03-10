<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Management\System;

use Yormy\AssertLaravel\Traits\RouteHelperTrait;
use Yormy\TripwireLaravel\Tests\TestCase;

class UsersTest extends TestCase
{
    use RouteHelperTrait;
    const ROUTE_RESET_KEY = 'api.v1.admin.members.account.tripwire.reset-key';

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function MemberBlocks_MemberLogs(): void
    {
        /*
         * Test if member logs can be retrieved by admin
         * Test if member blocks can be retrieved by admin
         */
        $this->markTestSkipped('Member Details logs/blocks untested');
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function AdminBlocks_AdminLogs(): void
    {
        /*
         * Test if logs can be retrieved by admin
         * Test if blocks can be retrieved by admin
         */
        $this->markTestSkipped('Admin Details logs/blocks untested');
    }
}
