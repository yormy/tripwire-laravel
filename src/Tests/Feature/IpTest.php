<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Yormy\TripwireLaravel\Tests\TestCase;

class IpTest extends TestCase
{
    public function testShouldAllow()
    {
        $this->assertTrue(true);
        //$this->assertEquals('next', (new Ip())->handle($this->app->request, $this->getNextClosure()));
    }

//    public function testShouldBlock()
//    {
//        Model::create(['ip' => '127.0.0.1', 'log_id' => 1]);
//
//        $this->assertEquals('403', (new Ip())->handle($this->app->request, $this->getNextClosure())->getStatusCode());
//    }
}
