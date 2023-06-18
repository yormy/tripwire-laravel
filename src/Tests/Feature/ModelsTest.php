<?php

namespace Yormy\TripwireLaravel\Tests\Feature\Middleware\Responses;

use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\ExceptionInspector;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class ModelsTest extends TestCase
{
    use TripwireTestTrait;

    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     * @group tripwire-models
     */
    public function Models_missing_log()
    {
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerModelNotFound();

        $this->assertLogAddedToDatabase($startCount);

        // todo test response
        // test block created if needed
    }

    private function triggerModelNotFound()
    {
        try {
            $failed = TripwireLog::findOrFail(999999);
        } catch (\Throwable $e) {
            ExceptionInspector::inspect($e);
        }
    }

    private function setDefaultConfig(array $data= [])
    {
        config(["tripwire.trigger_response.html" => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire.punish.score" => 21]);
    }
}
