<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yormy\TripwireLaravel\DataObjects\Config\UrlsConfig;
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
     * @group tripwire-page404
     */
    public function Page_missing_disabled_Trigger_Skip(): void
    {
        config(['tripwire_wires.page404.enabled' => false]);
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertNotLogged($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-page404
     */
    public function Page_missing_Added_Log(): void
    {
        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertLogAddedToDatabase($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-page404
     */
    public function Page_missing_Added_except_Skip(): void
    {
        $urls = UrlsConfig::make()
            ->except([
                'path/to/*',
            ]
            );
        config(['tripwire_wires.page404.urls' => $urls->toArray()]);

        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertNotLogged($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-page404
     */
    public function Page_missing_Added_only_Log(): void
    {
        $urls = UrlsConfig::make()
            ->only([
                'path/to/*',
            ]
            );
        config(['tripwire_wires.page404.urls' => $urls->toArray()]);

        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertLogAddedToDatabase($startCount);
    }

    /**
     * @test
     *
     * @group tripwire-page404
     */
    public function Page_missing_Added_only_mismatch_Skip(): void
    {
        $urls = UrlsConfig::make()
            ->only([
                'path/not/*',
            ]
            );
        config(['tripwire_wires.page404.urls' => $urls->toArray()]);

        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerPageNotFound();

        $this->assertNotLogged($startCount);
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
        (new ExceptionInspector())->inspect($exception, $request);
    }

    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.punish.score' => 21]);
    }

    private function setWireConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);
    }
}
