<?php
namespace Yormy\TripwireLaravel\Tests\Feature\Management\System;

use Illuminate\Testing\TestResponse;
use Yormy\AssertLaravel\Traits\RouteHelperTrait;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class BlocksTest extends TestCase
{
    use RouteHelperTrait;

    const ROUTE_INDEX = 'api.v1.admin.site.security.tripwire.blocks.index';

    const ROUTE_POST = 'api.v1.admin.site.security.tripwire.blocks.store';

    const ROUTE_SHOW_BLOCK = 'api.v1.admin.site.security.tripwire.blocks.show';

    const ROUTE_SHOW_BLOCK_LOGS = 'api.v1.admin.site.security.tripwire.blocks.logs.index';

    const ROUTE_SHOW_BLOCK_DELETE = 'api.v1.admin.site.security.tripwire.blocks.delete';

    const ROUTE_SHOW_BLOCK_UNBLOCK = 'api.v1.admin.site.security.tripwire.blocks.unblock';

    const ROUTE_SHOW_BLOCK_PERSIST = 'api.v1.admin.site.security.tripwire.blocks.persist';

    const ROUTE_SHOW_BLOCK_UNPERSIST = 'api.v1.admin.site.security.tripwire.blocks.unpersist';

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_Index_HasItem(): void
    {
        $data = $this->getBlockAddData();
        $response = $this->addBlockRecord($data);

        /** @var string $content */
        $content = $response->getContent();
        $blockedIp = json_decode($content)->data->blocked_ip; // @phpstan-ignore-line

        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayHasElement('blocked_ip', $blockedIp); // @phpstan-ignore-line
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
        $this->addBlockRecord($data);

        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayHasElement('blocked_ip', $data['blocked_ip']); // @phpstan-ignore-line
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
        $response->assertJsonDataItemHasElement('xid', $block->xid);  // @phpstan-ignore-line
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
        usleep(100); // need to wait a little, sometime the created record is not yet available

        $response = $this->json('GET', route(static::ROUTE_SHOW_BLOCK_LOGS, ['block_xid' => $block->xid]));
        $response->assertSuccessful();
        $response->assertJsonDataArrayHasElement('xid', $log->xid);  // @phpstan-ignore-line
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_Delete(): void
    {
        $data = $this->getBlockAddData();
        $response = $this->addBlockRecord($data);

        /** @var string $content */
        $content = $response->getContent();
        $blockedIp = json_decode($content)->data->blocked_ip; // @phpstan-ignore-line
        $blockedXid = json_decode($content)->data->xid; // @phpstan-ignore-line

        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayHasElement('blocked_ip', $blockedIp);  // @phpstan-ignore-line

        $this->json('DELETE', route(static::ROUTE_SHOW_BLOCK_DELETE, ['block_xid' => $blockedXid]));

        $response = $this->json('GET', route(static::ROUTE_INDEX));
        $response->assertJsonDataArrayNotHasElement('blocked_ip', $blockedIp);  // @phpstan-ignore-line

    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_Unblock(): void
    {
        $block = TripwireBlock::factory()->create();
        usleep(100); // need to wait a little, sometime the created record is not yet available

        $response = $this->json('PATCH', route(static::ROUTE_SHOW_BLOCK_UNBLOCK, ['block_xid' => $block->xid]));

        $response->assertSuccessful();
        /** @var string $content */
        $content = $response->getContent();
        $content = json_decode($content);
        $this->assertEquals($content?->data?->blocked_until, '');
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_Persist(): void
    {
        $data = $this->getBlockAddData();
        $response = $this->addBlockRecord($data);
        $xid = json_decode($response->getContent())->data->xid;

        $response = $this->json('PATCH', route(static::ROUTE_SHOW_BLOCK_PERSIST, ['block_xid' => $xid]));

        $response->assertSuccessful();
        $response->assertJsonDataItemHasElement('persistent_block', true);  // @phpstan-ignore-line
    }

    /**
     * @test
     *
     * @group tripwire-api
     */
    public function Blocks_Unpersist(): void
    {
        $data = $this->getBlockAddData();
        $response = $this->addBlockRecord($data);
        $xid = json_decode($response->getContent())->data->xid;

        $response = $this->json('PATCH', route(static::ROUTE_SHOW_BLOCK_UNPERSIST, ['block_xid' => $xid]));

        $response->assertSuccessful();
        $response->assertJsonDataItemHasElement('persistent_block', false);  // @phpstan-ignore-line
    }

    // --------- HELPERS ---------
    private function addBlockRecord(?array $data = null): TestResponse
    {
        if (! $data) {
            $data = $this->getBlockAddData();
        }

        $response = $this->json('POST', route(static::ROUTE_POST), $data);
        $response->assertCreated();

        return $response;
    }

    /**
     * @return array<string>
     */
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
        if (! $invalidValue) {
            unset($invalidData[$field]);
        } else {
            $invalidData[$field] = $invalidValue;
        }

        $response = $this->json('POST', $url, $invalidData);

        $response->assertJsonValidationErrors($field);
    }
}
