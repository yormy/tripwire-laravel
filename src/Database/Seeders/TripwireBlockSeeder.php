<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Seeder;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

class TripwireBlockSeeder extends Seeder
{
    public function run()
    {
        $user = UserResolver::getRandom();
        TripwireBlock::factory(1)->create();

        $block = TripwireBlock::factory()->forUser($user)->create();
        TripwireLog::factory(5)->forBlock($block)->forUser($user)->create();
    }
}
