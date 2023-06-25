<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\ExceptionInspector;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\RequestTrait;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class PageMissingTest extends TestCase
{
    use TripwireTestTrait;
    use RequestTrait;

    const HTTP_TRIPWIRE_CODE = 406;

    /**
     * @test
     *
     * @group tripwire-models
     */
    public function Page_missing_Added_log(): void
    {
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertLogAddedToDatabase($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-models
     */
    public function Page_missing_Added_block(): void
    {
        $startCount = TripwireBlock::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();
        $this->triggerPageNotFound();
        $this->triggerPageNotFound();

        $endCount = TripwireBlock::count();

        $this->assertGreaterThan($startCount, $endCount);
    }

    private function triggerPageNotFound(): void
    {
        $request = $this->createRequest('post', '', 'path/to/location');
        $request->url();

        $exception = (new NotFoundHttpException($request));
        ExceptionInspector::inspect($exception, $request);

    }

    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.punish.score' => 21]);
    }

    private function setWireConfig(array $data = []): void
    {
        config(['tripwire.trigger_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);
    }
}
