<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Yormy\TripwireLaravel\Models\TripwireBlock;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Services\Resolvers\UserResolver;

class TripwireBlockSeeder extends Seeder
{
    public function run($user = null)
    {
        TripwireBlock::factory(1)->create();

        $member = UserResolver::getMemberById(1);
        $this->createForUser($member);

        $member = UserResolver::getMemberById(2);
        $this->createForUser($member);

        $admin = UserResolver::getAdminById(1);
        $this->createForUser($admin);

        $admin = UserResolver::getAdminById(2);
        $this->createForUser($admin);

    }

    private function createForUser(Model $user)
    {
        $block = TripwireBlock::factory()->forUser($user)->create();
        TripwireLog::factory(5)->forBlock($block)->forUser($user)->create();
    }
}
