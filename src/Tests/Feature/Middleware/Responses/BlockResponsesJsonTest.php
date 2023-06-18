<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\BlockTestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BlockResponsesJsonTest extends TestCase
{
    use BlockTestTrait;
    use TripwireTestTrait;

    private string $tripwire = 'text';

    protected $tripwireClass = Text::class;

    const BLOCK_CODE = 401;

    const TRIPWIRE_TRIGGER = 'JSON-RESPONSE-TEST';

    /**
     * @test
     *
     * @group tripwire-block
     *
     * @return never
     */
    public function json_blocked_Request_Should_block_with_exception()
    {

        $this->setConfig();
        $settings = ['exception' => TripwireFailedException::class];
        config(['tripwire.block_response.json' => $settings]);

        $this->triggerJsonBlock();

        $this->expectException(TripwireFailedException::class);
        $result = $this->doJsonRequest();

        dd($result);
    }

    /**
     * @test
     *
     * @group tripwire-block
     */
    public function Json_blocked_Request_Should_block_with_code(): void
    {
        $this->setConfig();
        $settings = ['code' => self::BLOCK_CODE];
        config(['tripwire.block_response.json' => $settings]);

        $this->triggerJsonBlock();

        $result = $this->doJsonRequest();

        $this->assertFirewallTripped($result, self::BLOCK_CODE);
    }

    /**
     * @test
     *
     * @group tripwire-block
     */
    public function Json_blocked_Request_Should_block_with_message(): void
    {
        $messageKey = 'json.message';

        $this->setConfig();
        $settings = ['message_key' => $messageKey];
        config(['tripwire.block_response.json' => $settings]);

        $this->triggerJsonBlock();

        $result = $this->doJsonRequest();

        $this->assertEquals($result->getOriginalContent(), $messageKey);
    }

    private function triggerJsonBlock(): void
    {
        $this->resetBlockStartCount();

        $this->triggerJsonTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerJsonTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerJsonTripwire(self::TRIPWIRE_TRIGGER);
    }

    protected function setConfig(): void
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.trigger_response.json" => $settings]);
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);

        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(['tripwire.punish.score' => 21]);

        $this->setBlockConfig();
    }
}
