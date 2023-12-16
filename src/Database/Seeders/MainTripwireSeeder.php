<?php

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Seeder;

class MainTripwireSeeder extends Seeder
{
    public function run()
    {
        (new TripwireLogSeeder())->run();
        (new TripwireBlockSeeder())->run();
    }
}
