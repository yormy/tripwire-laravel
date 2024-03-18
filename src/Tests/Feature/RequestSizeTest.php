<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Wires\RequestSize;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class RequestSizeTest extends TestCase
{
    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     *
     * @group tripwire-wires
     */
    public function Trigger_Short_request_Oke(): void
    {
        $this->setDefaultConfig();
        $result = $this->testRequest();
        $this->assertOke($result);
    }

    /**
     * @test
     *
     * @group tripwire-wires
     */
    public function Trigger_Long_request_Log(): void
    {
        $startCount = Tripwirelog::count();

        $this->setDefaultConfig();
        config([
            'tripwire_wires.request_size.attack_score' => 10,
            'tripwire_wires.request_size.tripwires' => [
                'size' => 10,
            ],
        ]);

        $result = $this->testRequest();
        $this->assertBlock($result);

        $endCount = Tripwirelog::count();

        $this->assertGreaterThan($startCount, $endCount);
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

    private function testRequest(): mixed
    {
        $request = request();
        $request->query->set('foo', 'non blocked test');

        return (new RequestSize($request))->handle($request, $this->getNextClosure());
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @var array<string> $data
     */
    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);
    }
}
