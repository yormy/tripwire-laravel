<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Seeder;

class MainTripwireSeeder extends Seeder
{
    public function run(Authenticatable $user = null): void
    {
        (new TripwireLogSeeder($user))->run();
        (new TripwireBlockSeeder($user))->run();
    }
}
