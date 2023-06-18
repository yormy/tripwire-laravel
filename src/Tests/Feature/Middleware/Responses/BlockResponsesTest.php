<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Http\Middleware\Wires\Text;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\BlockTestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class BlockResponsesTest extends TestCase
{
    use BlockTestTrait;
    use TripwireTestTrait;

    private string $tripwire = 'text';

    protected $tripwireClass = Text::class;

    const BLOCK_CODE = 401;

    const TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';

    /**
     * @test
     *
     * @group tripwire-block
     */
    public function Blocked_Request_Should_block_with_code()
    {
        $this->setConfig();

        $this->triggerBlock();

        $result = $this->doRequest();

        $this->assertFirewallTripped($result, self::BLOCK_CODE);
    }

    /**
     * @test
     *
     * @group tripwire-block
     */
    public function Blocked_Request_Should_block_with_redirecturl()
    {
        $redirectUrl = 'https://www.cccc.com';
        $this->setConfig();
        $settings = ['redirect_url' => $redirectUrl];
        config(['tripwire.block_response.html' => $settings]);

        $this->triggerBlock();

        $result = $this->doRequest();

        $this->assertEquals($result->getTargetUrl(), $redirectUrl);
        $this->assertEquals($result->getStatusCode(), 302);
    }

    /**
     * @test
     *
     * @group tripwire-block
     */
    public function Blocked_Request_Should_block_with_view()
    {
        $viewName = 'tripwire-laravel::blocked';

        $this->setConfig();
        $settings = ['view' => $viewName];
        config(['tripwire.block_response.html' => $settings]);

        $this->triggerBlock();

        $result = $this->doRequest();

        $this->assertEquals($result->getOriginalContent()->name(), $viewName);
    }

    /**
     * @test
     *
     * @group tripwire-block
     */
    public function Blocked_Request_Should_block_with_message()
    {
        $messageKey = 'message.key';

        $this->setConfig();
        $settings = ['message_key' => $messageKey];
        config(['tripwire.block_response.html' => $settings]);

        $this->triggerBlock();

        $result = $this->doRequest();

        $this->assertEquals($result->getOriginalContent(), $messageKey);
    }

    protected function setConfig()
    {
        $settings = ['code' => 409];
        config(["tripwire_wires.$this->tripwire.enabled" => true]);
        config(["tripwire_wires.$this->tripwire.methods" => ['*']]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);

        config(["tripwire_wires.$this->tripwire.attack_score" => 10]);
        config(['tripwire.punish.score' => 21]);

        $this->setBlockConfig();
    }

    private function triggerBlock()
    {
        $this->resetBlockStartCount();

        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
        $this->triggerTripwire(self::TRIPWIRE_TRIGGER);
    }
}
