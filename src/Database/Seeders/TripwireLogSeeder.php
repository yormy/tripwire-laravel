<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Database\Seeders;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Seeder;
use Yormy\TripwireLaravel\Models\TripwireLog;
use Yormy\TripwireLaravel\Tests\Setup\Models\Admin;
use Yormy\TripwireLaravel\Tests\Setup\Models\Member;

class TripwireLogSeeder extends Seeder
{
    public function __construct()
    {
        // ...
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function run(?Authenticatable $user = null): void
    {
        $member = Member::create();
        TripwireLog::factory(10)->forUser($member)->create();

        $member = Member::create();
        TripwireLog::factory(10)->forUser($member)->create();

        $admin = Admin::create();
        TripwireLog::factory(10)->forUser($admin)->create();

        $admin = Admin::create();
        TripwireLog::factory(10)->forUser($admin)->create();
    }
}
