<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Illuminate\Http\Request;
use Yormy\TripwireLaravel\DataObjects\Config\BlockResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\HtmlResponseConfig;
use Yormy\TripwireLaravel\DataObjects\Config\JsonResponseConfig;
use Yormy\TripwireLaravel\Http\Middleware\ChecksumValidator;
use Yormy\TripwireLaravel\Services\HashService;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class ChecksumTest extends TestCase
{
    use TripwireTestTrait;

    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     *
     * @group tripwire-checksum
     */
    public function Checksum_Trigger_Skip(): void
    {
        $this->setDefaultConfig();
        config(['tripwire_wires.checksum.enabled' => false]);

        $result = $this->trigger();

        $this->assertOke($result);
    }

    /**
     * @test
     *
     * @group tripwire-checksum
     */
    public function Checksum_Trigger_Log(): void
    {
        $this->setDefaultConfig();

        $result = $this->trigger();

        $this->assertBlocked($result);
    }

    /**
     * @test
     *
     * @group tripwire-checksum
     */
    public function Checksum_Trigger_correct_Oke(): void
    {
        $this->setDefaultConfig();

        $result = $this->trigger(true);

        $this->assertOke($result);
    }

    // ---------- HELPERS ---------

    public function assertOke($result): void
    {
        $this->assertEquals('next', $result);
    }

    public function assertBlocked($result): void
    {
        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }

    // ---------- HELPERS ----------
    private function trigger(bool $withChecksum = false): mixed
    {
        $requestData = ['firstname' => 'just-dummy-data'];
        $request = Request::create('/dummy/route', 'POST', $requestData);

        if ($withChecksum) {
            $request->headers->set('X-Checksum', $this->calculateChecksum($request));
        } else {
            $request->headers->set('X-Checksum', 'invalid-checksum-number');
        }

        return (new ChecksumValidator())->handle($request, $this->getNextClosure());
    }

    private function calculateChecksum($request): string
    {
        $data = $request->except(array_keys($request->query()));
        $requestJson = json_encode($data, JSON_UNESCAPED_UNICODE);
        $requestCleaned = $requestJson;
        $requestCleaned = preg_replace('/[^a-z0-9]/', '', $requestCleaned);

        return HashService::create($requestCleaned);
    }

    private function setDefaultConfig(array $data = []): void
    {
        $config = [
            'posted' => 'X-Checksum',
            'timestamp' => 'X-sand',
            'serverside_calculated' => 'x-checksum-serverside',
        ];

        config(['tripwire_wires.checksum.config' => $config]);

        $config = BlockResponseConfig::make(
            JsonResponseConfig::make()->code(self::HTTP_TRIPWIRE_CODE),
            HtmlResponseConfig::make()->code(self::HTTP_TRIPWIRE_CODE),
        );
        config(['tripwire_wires.checksum.reject_response' => $config->toArray()]);

    }
}
