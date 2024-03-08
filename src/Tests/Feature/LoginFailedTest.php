<?php

namespace Yormy\TripwireLaravel\Tests\Feature;

use Illuminate\Auth\Events\Failed as LoginFailed;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\TestCase;
use Yormy\TripwireLaravel\Tests\Traits\TripwireTestTrait;

class LoginFailedTest extends TestCase
{
    use TripwireTestTrait;

    const HTTP_TRIPWIRE_CODE = 409;

    /**
     * @test
     *
     */
    public function LoginFailed_Trigger_Skip(): void
    {
        config(['tripwire_wires.loginfailed.enabled' => false]);

        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerLoginFailed();

        $this->assertNotLogged($startCount);
    }

    /**
     * @test
     *
     */
    public function LoginFailed_Trigger_Log(): void
    {
        config(['tripwire_wires.loginfailed.enabled' => true]);

        $startCount = TripwireLog::count();

        $this->setDefaultConfig();

        $this->triggerLoginFailed();

        $this->assertLogAddedToDatabase($startCount);
    }

    // ---------- HELPERS ----------
    private function triggerLoginFailed(): void
    {
        event(new LoginFailed('', '', ''));
    }

    private function setDefaultConfig(array $data = []): void
    {
        config(['tripwire.reject_response.html' => ['code' => self::HTTP_TRIPWIRE_CODE]]);
        config(['tripwire.punish.score' => 21]);

        //        $config = MissingModelConfig::make()->only([Tripwirelog::class,])->except([]);
        //        config(['tripwire_wires.model404.tripwires' => [$config]]);
    }
}
