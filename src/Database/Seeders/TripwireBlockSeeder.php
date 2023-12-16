<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;

class TripwireBlockSeeder extends Seeder
{
    public function run()
    {
        TripwireBlock::factory(1)->create();

        $block = TripwireBlock::factory()->create();

        TripwireLog::factory(5)->forBlock($block)->create();
    }
}
