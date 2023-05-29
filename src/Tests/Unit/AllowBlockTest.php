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
    public function AllowBlock_EmptyFilter_Allow(): void
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

        $filters = [
            'allow' => [],
            'block' => [],
        ];

        $this->expectException(\Exception::class);
        CheckAllowBlock::shouldBlock('test', $filters);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_AllowValue_Allow(): void
    {

        $filters = [
            'allow' => ['firefox'],
            'block' => [],
        ];

        $result = CheckAllowBlock::shouldBlock('firefox', $filters);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_BlockValue_Block(): void
    {

        $filters = [
            'allow' => ['*'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('brave', $filters);
        $this->assertTrue($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_BlockNotValue_Allow(): void
    {

        $filters = [
            'allow' => ['*'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('chome', $filters);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_BlockEmpty_Allow(): void
    {

        $filters = [
            'allow' => ['*'],
            'block' => [],
        ];

        $result = CheckAllowBlock::shouldBlock('firebrave', $filters);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Allow_and_block_value_Allow(): void
    {

        $filters = [
            'allow' => ['firefox'],
            'block' => ['firefox'],
        ];

        $result = CheckAllowBlock::shouldBlock('firefox', $filters);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Unspecified_DefaultAllow(): void
    {

        $filters = [
            'allow' => ['firefox'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('chrome', $filters, false);
        $this->assertFalse($result);
    }

    /**
     * @test
     *
     * @group tripwire-allow_block
     */
    public function AllowBlock_Unspecified_DefaultBlock(): void
    {

        $filters = [
            'allow' => ['firefox'],
            'block' => ['brave'],
        ];

        $result = CheckAllowBlock::shouldBlock('chrome', $filters, true);
        $this->assertTrue($result);
    }
}
