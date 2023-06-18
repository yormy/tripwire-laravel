<?php

namespace Yormy\TripwireLaravel\Tests\Traits;

use Yormy\TripwireLaravel\Http\Middleware\Blockers\TripwireBlockHandlerAll;
use Yormy\TripwireLaravel\Models\TripwireBlock;

trait BlockTestTrait
{
    protected function setBlockConfig()
    {
        $settings = ['code' => self::BLOCK_CODE];
        config(["tripwire.block_response.html" => $settings]);
    }

    protected function doRequest()
    {
        $request = $this->app->request;
        $blocker = new TripwireBlockHandlerAll();

        return $blocker->handle($request, $this->getNextClosure());
    }

    protected function doJsonRequest()
    {
        $request = $this->app->request;
        $request->headers->set('Accept', 'application/json');
        $blocker = new TripwireBlockHandlerAll();

        return $blocker->handle($request, $this->getNextClosure());
    }

    protected function resetBlockStartCount(): int
    {
        TripwireBlock::truncate();

        return TripwireBlock::count();
    }

    protected function assertBlockAddedToDatabase($startCount)
    {
        $this->assertGreaterThan($startCount, TripwireBlock::count());
    }

    protected function assertNotBlocked($startCount)
    {
        $this->assertEquals($startCount, TripwireBlock::count());
    }
}
