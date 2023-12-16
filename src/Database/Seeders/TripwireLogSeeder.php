<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Yormy\TripwireLaravel\Models\TripwireLog;

class TripwireLogSeeder extends Seeder
{
    public function run()
    {
        TripwireLog::factory()->create();
    }
}
