<?php

namespace Yormy\TripwireLaravel\Tests\Unit;

use Yormy\TripwireLaravel\Services\CheckAllowBlock;
use Yormy\TripwireLaravel\Tests\TestCase;

class AllowBlockTest extends TestCase
{
    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_EmptyGuards_Allow(): void
    {
        $result = CheckAllowBlock::shouldBlock('test', []);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Empty_allow_Exception(): void
    {

        $guards = [
            'allow' => [],
            'block' => [],
        ];

        $this->expectException(\Exception::class);
        CheckAllowBlock::shouldBlock('test', $guards);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_AllowValue_Allow(): void
    {

        $guards = [
            'allow' => ['firefox'],
            'block' => [],
        ];

        $result = CheckAllowBlock::shouldBlock('firefox', $guards);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_BlockValue_Block(): void
    {

        $guards = [
            'allow' => ['*'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('brave', $guards);
        $this->assertTrue($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_BlockNotValue_Allow(): void
    {

        $guards = [
            'allow' => ['*'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('chome', $guards);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_BlockEmpty_Allow(): void
    {

        $guards = [
            'allow' => ['*'],
            'block' => [],
        ];

        $result = CheckAllowBlock::shouldBlock('firebrave', $guards);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Allow_and_block_value_Allow(): void
    {

        $guards = [
            'allow' => ['firefox'],
            'block' => ['firefox'],
        ];

        $result = CheckAllowBlock::shouldBlock('firefox', $guards);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Unspecified_DefaultAllow(): void
    {

        $guards = [
            'allow' => ['firefox'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('chrome', $guards, false);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Unspecified_DefaultBlock(): void
    {

        $guards = [
            'allow' => ['firefox'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('chrome', $guards, true);
        $this->assertTrue($result);
    }
}
