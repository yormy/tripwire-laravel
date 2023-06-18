<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yormy\TripwireLaravel\Tests\Traits\RequestTrait;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\ExceptionInspector;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class PageMissingTest extends TestCase
{
    use TripwireTestTrait;
    use RequestTrait;

    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     * @group aaa
     */
    public function Page_missing_log()
    {
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertLogAddedToDatabase($startCount);

        // todo test response
        // test block created if needed
    }

    private function triggerPageNotFound()
    {
        $request = $this->createRequest('post','', 'path/to/location');
        $request->url();

        $exception = (new NotFoundHttpException($request));
        ExceptionInspector::inspect($exception, $request);

    }

    private function setDefaultConfig(array $data= [])
    {
        config(["tripwire.trigger_response.html" => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(["tripwire.punish.score" => 21]);
    }
}
