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
    /**
     * @test
     */
    public function trigger_as_exception_expects_exception()
    {
        $settings = [
            "exception" => TripwireFailedException::class
        ];

        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);

        $this->expectException(TripwireFailedException::class);

        $this->triggerTripwire();
    }


    /**
     * @test
     */
    public function trigger_as_code_expects_code()
    {
        $settings = [
            "code" => self::HTTP_TRIPWIRE_CODE
        ];
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getStatusCode(), self::HTTP_TRIPWIRE_CODE);
    }


    /**
     * @test
     */
    public function trigger_as_redirecturl_expects_redirecturl()
    {
        $redirectUrl = "https://www.cccc.com";

        $settings = [
            "redirect_url" => $redirectUrl
        ];
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertEquals($result->getTargetUrl(), $redirectUrl);
        $this->assertLogAddedToDatabase($startCount);
        $this->assertEquals($result->getStatusCode(), 302);
    }


    /**
     * @test
     */
    public function trigger_as_view_expects_view()
    {
        $viewName = "tripwire-laravel::blocked";

        $settings = [
            "view" => $viewName
        ];
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent()->name(), $viewName);
    }

    /**
     * @test
     */
    public function trigger_as_message_expects_message()
    {
        $messageKey = "message.key";

        $settings = [
            "message_key" => $messageKey
        ];
        config(["tripwire_wires.$this->tripwire.trigger_response.html" => $settings]);

        $startCount = TripwireLog::count();

        $result = $this->triggerTripwire();

        $this->assertLogAddedToDatabase($startCount);

        $this->assertEquals($result->getOriginalContent(), $messageKey);
    }

    private function triggerTripwire()
    {
        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', 'aaa');

        return (new Text($request))->handle($request, $this->getNextClosure());
    }

    private function assertLogAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }
}
