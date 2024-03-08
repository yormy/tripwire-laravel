<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Management;

use Illuminate\Support\Carbon;
use Yormy\AssertLaravel\Traits\DisableExceptionHandling;
use Yormy\AssertLaravel\Traits\RouteHelperTrait;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;
use Yormy\TripwireLaravel\Tests\TestCase;

class BlocksTest extends TestCase
{
    use RouteHelperTrait;

    const ROUTE_INDEX = 'api.v1.admin.site.security.tripwire.blocks.index';
    const ROUTE_POST = 'api.v1.admin.site.security.tripwire.blocks.store';

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

        TripwireBlock::factory(1)->create(['blocked_ip' => $blockedIp]);
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
