<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Seeder;
use Mexion\BedrockUsersv2\Domain\User\Models\Admin;
use Mexion\BedrockUsersv2\Domain\User\Models\Member;
use Yormy\TripwireLaravel\Models\TripwireLog;

class TripwireLogSeeder extends Seeder
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function run(?Authenticatable $user = null): void
    {
        $member = Member::where('id', 1)->first();
        TripwireLog::factory(10)->forUser($member)->create();

        $member = Member::where('id', 2)->first();
        TripwireLog::factory(10)->forUser($member)->create();

        $admin = Admin::where('id', 1)->first();
        TripwireLog::factory(10)->forUser($admin)->create();

        $admin = Admin::where('id', 2)->first();
        TripwireLog::factory(10)->forUser($admin)->create();
    }
}
