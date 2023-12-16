<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

class TripwireLogSeeder extends Seeder
{
    public function run()
    {
        $user = UserResolver::getRandom();
        TripwireLog::factory(10)->forUser($user)->create();
    }
}
