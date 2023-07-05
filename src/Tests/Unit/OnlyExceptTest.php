<?php

namespace Yormy\TripwireLaravel\Tests\Unit;

use Yormy\TripwireLaravel\DataObjects\Config\MissingModelConfig;
use Yormy\TripwireLaravel\DataObjects\Config\MissingPageConfig;
use Yormy\TripwireLaravel\DataObjects\Config\OnlyExceptConfig;
use Yormy\TripwireLaravel\Services\CheckAllowBlock;
use Yormy\TripwireLaravel\Services\CheckOnlyExcept;
use Yormy\TripwireLaravel\Tests\TestCase;

class OnlyExceptTest extends TestCase
{

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_MissingConfig_Process(): void
    {
        $config = MissingPageConfig::make();

        $result = CheckOnlyExcept::needsProcessing('firefox', $config);
        $this->assertTrue($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_EmptyConfig_Process(): void
    {

        $config = MissingPageConfig::make()
            ->only([])
            ->except([]);;

        $result = CheckOnlyExcept::needsProcessing('firefox', $config);
        $this->assertTrue($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_OnlyValue_Process(): void
    {
        $config = MissingPageConfig::make()
            ->only(['firefox'])
            ->except([]);

        $result = CheckOnlyExcept::needsProcessing('firefox', $config);
        $this->assertTrue($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_OnlyValueMismatch_Skip(): void
    {
        $config = MissingPageConfig::make()
            ->only(['firefox'])
            ->except([]);

        $result = CheckOnlyExcept::needsProcessing('chrome', $config);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_ExceptValueMismatch_Process(): void
    {
        $config = MissingPageConfig::make()
            ->only([])
            ->except(['firefox']);

        $result = CheckOnlyExcept::needsProcessing('chrome', $config);
        $this->assertTrue($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_ExceptValue_Skip(): void
    {

        $config = MissingPageConfig::make()
            ->only([])
            ->except(['firefox']);

        $result = CheckOnlyExcept::needsProcessing('firefox', $config);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_OnlyAndExceptValue_Skip(): void
    {

        $config = MissingPageConfig::make()
            ->only(['firefox'])
            ->except(['firefox']);

        $result = CheckOnlyExcept::needsProcessing('firefox', $config);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-only_except
     */
    public function OnlyExcept_OnlyMismatch_Skip(): void
    {

        $config = MissingPageConfig::make()
            ->only(['falcon'])
            ->except([]);

        $result = CheckOnlyExcept::needsProcessing('chrome', $config);
        $this->assertFalse($result);
    }
}
