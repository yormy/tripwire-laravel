<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Geo;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\Fakes\FakeIpLookup;
use Yormy\TripwireLaravel\Tests\TestCase;

class GeoTest extends TestCase
{
    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     *
     * @group geo
     */
    public function Trigger_Geo_Oke(): void
    {
        $this->setDefaultConfig();
        $result = $this->testRequest();
        $this->assertOke($result);
    }

    /**
     * @test
     *
     * @group geo
     */
    public function Trigger_Geo_continent_Block(): void
    {
        $startCount = Tripwirelog::count();

        $this->setDefaultConfig();

        $continents = [
            'allow' => ['*'],
            'block' => ['CONTINENT'],
        ];

        config(['tripwire_wires.geo.tripwires.continents' => $continents]);

        $result = $this->testRequest();
        $this->assertBlock($result);

        $endCount = Tripwirelog::count();

        $this->assertGreaterThan($startCount, $endCount);
    }

    /**
     * @test
     *
     * @group geo
     */
    public function Trigger_Geo_region_Block(): void
    {
        $this->setDefaultConfig();

        $regions = [
            'allow' => ['*'],
            'block' => ['REGION'],
        ];

        config(['tripwire_wires.geo.tripwires.regions' => $regions]);

        $result = $this->testRequest();
        $this->assertBlock($result);
    }

    /**
     * @test
     *
     * @group geo
     */
    public function Trigger_Geo_country_Block(): void
    {
        $this->setDefaultConfig();

        $countries = [
            'allow' => ['*'],
            'block' => ['COUNTRY'],
        ];

        config(['tripwire_wires.geo.tripwires.countries' => $countries]);

        $result = $this->testRequest();
        $this->assertBlock($result);
    }

    /**
     * @test
     *
     * @group geo
     */
    public function Trigger_Geo_city_Block(): void
    {
        $this->setDefaultConfig();

        $cities = [
            'allow' => ['*'],
            'block' => ['CITY'],
        ];

        config(['tripwire_wires.geo.tripwires.cities' => $cities]);

        $result = $this->testRequest();
        $this->assertBlock($result);
    }

    // -------- HELPERS --------
    public function assertBlock(mixed $result): void
    {
        $this->assertNotEquals('next', $result);
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    public function assertOke(mixed $result): void
    {
        $this->assertEquals('next', $result);
    }

    private function testRequest()
    {
        $request = request();
        $request->query->set('foo', 'non blocked test');

        return (new Geo($request))->handle($request, $this->getNextClosure());
    }

    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);

        config(['tripwire_wires.geo.tripwires.service' => FakeIpLookup::class]);
    }
}
