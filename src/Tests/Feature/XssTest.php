<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Http\Middleware\Checkers\Xss;
use Yormy\TripwireLaravel\Tests\TestCase;

class XssTest extends TestCase
{
    public function testShouldAllow()
    {
        $next = (new Xss($this->app->request))->handle($this->app->request, $this->getNextClosure());
        dd($next);
        $this->assertEquals('next', $next);
    }

    public function testShouldBlock()
    {
        $this->app->request->query->set('foo', '<script>alert(123)</script>');

        $next = (new Xss($this->app->request))->handle($this->app->request, $this->getNextClosure());
        dd($next);
        $this->assertEquals('403', $next->getStatusCode());
    }
}
