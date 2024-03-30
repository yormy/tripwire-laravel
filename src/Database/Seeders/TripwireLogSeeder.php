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
        $memberClass = config('tripwire.models.member');
        $adminClass = config('tripwire.models.admin');

        $member = $memberClass::factory()->create();
        TripwireLog::factory(10)->forUser($member)->create();

        $member = $memberClass::factory()->create();
        TripwireLog::factory(10)->forUser($member)->create();

        $admin = $adminClass::factory()->create();
        TripwireLog::factory(10)->forUser($admin)->create();

        $admin = $adminClass::factory()->create();
        TripwireLog::factory(10)->forUser($admin)->create();
    }
}
