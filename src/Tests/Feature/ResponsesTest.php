<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class ResponsesTest extends TestCase
{
    private string $tripwire ='text';
    const HTTP_TRIPWIRE_CODE = 409;

    CONST TRIPWIRE_TRIGGER = 'HTML-RESPONSE-TEST';
    /**
     * @test
     */
    public function respond_as_exception_expects_exception()
    {
        $this->setConfig(["exception" => TripwireFailedException::class]);

        $this->expectException(TripwireFailedException::class);

        $this->triggerTripwire();
    }


    /**
     * @test
     */
    public function respond_as_code_expects_code()
    {
        $this->setConfig(["code" => self::HTTP_TRIPWIRE_CODE]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }


    /**
     * @test
     */
    public function respond_as_redirecturl_expects_redirecturl()
    {
        $redirectUrl = "https://www.cccc.com";
        $this->setConfig(["redirect_url" => $redirectUrl]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertEquals($result->getTargetUrl(), $redirectUrl);
        $this->assertLogAddedToDatabase($startCount);
        $this->assertEquals($result->getStatusCode(), 302);
    }


    /**
     * @test
     */
    public function respond_as_view_expects_view()
    {
        $viewName = "tripwire-laravel::blocked";
        $this->setConfig(["view" => $viewName]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent()->name(), $viewName);
    }



    /**
     * @test
     */
    public function respond_as_message_expects_message()
    {
        $messageKey = "message.key";
        $this->setConfig(["message_key" => $messageKey]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent(), $messageKey);
    }

    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', self::TRIPWIRE_TRIGGER);

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function assertLogAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }

    private function setConfig(array $data)
    {
        config(["tripwire_wires.$this->tripwire.tripwires" => [self::TRIPWIRE_TRIGGER]]);
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $data]);
    }
}
