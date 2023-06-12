<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class ResponsesJsonTest extends TestCase
{
    private string $tripwire ='text';
    const HTTP_TRIPWIRE_CODE = 409;

    CONST TRIPWIRE_TRIGGER = 'JSON-RESPONSE-TEST';

    /**
     * @test
     * @group tripwire-response
     */
    public function json_respond_missing_expects_default_exception()
    {
        $this->setDefaultConfig(["exception" => TripwireFailedException::class]);

        $this->expectException(TripwireFailedException::class);

        $this->triggerTripwire();
    }

    /**
     * @test
     * @group tripwire-response
     */
    public function json_respond_as_exception_expects_exception()
    {
        $this->setConfig(["exception" => TripwireFailedException::class]);

        $this->expectException(TripwireFailedException::class);

        $this->triggerTripwire();
    }

    /**
     * @test
     * @group tripwire-response
     */
    public function json_respond_missing_expects_default_message()
    {
        $messageKey = "message.key";
        $this->setDefaultConfig(["message_key" => $messageKey]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent(), $messageKey);
    }

    /**
     * @test
     * @group tripwire-response
     */
    public function json_respond_as_message_expects_message()
    {
        $messageKey = "message.key";
        $this->setConfig(["message_key" => $messageKey]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent(), $messageKey);
    }


    /**
     * @test
     * @group tripwire-response
     */
    public function json_respond_missing_expects_default_json()
    {
        $json =  ['data' => 'somedata', 'err' =>'2'];
        $this->setDefaultConfig(["json" => $json]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent(), $json);
    }

    /**
     * @test
     * @group tripwire-response
     */
    public function json_respond_as_json_expects_json()
    {
        $json =  ['data' => 'somedata', 'err' =>'2'];
        $this->setConfig(["json" => $json]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent(), $json);
    }

    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);
        $request->headers->set('Accept', 'application/json');

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function assertLogAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }

    private function setConfig(array $data)
    {
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire_wires.$this->tripwire.trigger_response.json" => $data]);
    }

    private function setDefaultConfig(array $data)
    {
        config(["tripwire_wires.$this->tripwire.trigger_response.json" => []]);

        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire.trigger_response.json" => $data]);
    }
}
