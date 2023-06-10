<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Exceptions\TripwireFailedException;
use Yormy\TripwireLaravel\Http\Middleware\Checkers\Text;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;

class TextTest extends TestCase
{
//    public function testShouldAllow()
//    {
////        $next = (new Xss($this->app->request))->handle($this->app->request, $this->getNextClosure());
////
////        $this->assertEquals('next', $next);
//    }

    public function testShouldBlock()
    {
       // $this->expectException(TripwireFailedException::class);

        $startCount = TripwireLog::count();

        $request = $this->app->request; // default is as HTML
        $request->query->set('foo', 'aaa');
        $result = (new Text($request))->handle($request, $this->getNextClosure());

        $this->assertLogAddedToDatabase($startCount);

        $this->assertFirewallTripped($result);

    }

    private function assertLogAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireLog::count());
    }

    private function assertFirewallTripped($result)
    {
        $this->assertEquals($result->getStatusCode(), 409);
    }
}
