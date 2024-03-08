<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Management;

use Illuminate\Support\Carbon;
use Yormy\AssertLaravel\Traits\DisableExceptionHandling;
use Yormy\AssertLaravel\Traits\RouteHelperTrait;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;
use Yormy\TripwireLaravel\Tests\TestCase;

class BlocksTest extends TestCase
{
    use RouteHelperTrait;

    const ROUTE_INDEX = 'api.v1.admin.site.security.tripwire.blocks.index';
    const ROUTE_POST = 'api.v1.admin.site.security.tripwire.blocks.store';

    const ROUTE_SHOW_BLOCK = 'api.v1.admin.site.security.tripwire.blocks.show';
    const ROUTE_SHOW_BLOCK_LOGS = 'api.v1.admin.site.security.tripwire.blocks.logs.index';

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_Index_HasItem(): void
    {
        $blockedIp = '0.0.0';
        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertSuccessful();
        $response->assertJsonDataArrayNotHasElement('blocked_ip', $blockedIp);

        TripwireBlock::factory()->create(['blocked_ip' => $blockedIp]);
        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayHasElement('blocked_ip', $blockedIp);
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_InvalidParameters_Error(): void
    {
        $this->withExceptionHandling();
        $data = $this->getBlockAddData();

        $this->ruleTestParameter($data, route(static::ROUTE_POST), 'blocked_ip');
        $this->ruleTestParameter($data, route(static::ROUTE_POST), 'blocked_ip', '023');
        $this->ruleTestParameter($data, route(static::ROUTE_POST), 'internal_comments');
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_AddItem_HasItem(): void
    {
        $data = $this->getBlockAddData();
        $response = $this->json('POST', route(static::ROUTE_POST), $data);
        $response->assertCreated();

        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayHasElement('blocked_ip', $data['blocked_ip']);
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_ShowBlock(): void
    {
        $block = TripwireBlock::factory()->create();
        $response = $this->json('GET', route(static::ROUTE_SHOW_BLOCK, ['block_xid' => $block->xid]));
        $response->assertSuccessful();
        $response->assertJsonDataItemHasElement('xid', $block->xid);
    }

    /**
     * @test
     *
     * @group tripwire-api
     * @group xxx
     */
    public function Blocks_ShowBlockLogs(): void
    {
        $block = TripwireBlock::factory()->create();
        $log = TripwireLog::factory()->create(['tripwire_block_id' => $block->id]);

        $response = $this->json('GET', route(static::ROUTE_SHOW_BLOCK_LOGS, ['block_xid' => $block->xid]));
        $response->assertSuccessful();
        $response->assertJsonDataArrayHasElement('xid', $log->xid);
    }

    // --------- HELPERS ---------
    private function getBlockAddData(): array
    {
        $data = [];
        $data['blocked_ip'] = '000.000.000';
        $data['internal_comments'] = 'note internal comment';

        return $data;
    }

    private function ruleTestParameter(array $validData, string $url, string $field, $invalidValue = null)
    {
        $invalidData = $validData;
        if (!$invalidValue) {
            unset($invalidData[$field]);
        } else {
            $invalidData[$field] = $invalidValue;
        }

        $response = $this->json('POST', $url, $invalidData);

        $response->assertJsonValidationErrors($field);
    }

}
