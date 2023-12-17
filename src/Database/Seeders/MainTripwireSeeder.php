<?php

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Seeder;

class MainTripwireSeeder extends Seeder
{
    public function run($user = null)
    {
        (new TripwireLogSeeder($user))->run();
        (new TripwireBlockSeeder($user))->run();
    }
}
