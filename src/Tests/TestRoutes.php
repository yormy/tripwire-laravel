<?php declare(strict_types=1);

namespace Yormy\TripwireLaravel\Tests;

use Illuminate\Support\Facades\Route;

class TestRoutes
{
    public static function setup()
    {
        Route::prefix('admin2/')
            ->name('api.v1.admin.')
            ->middleware('api')
            ->group(function () {
                Route::prefix('site/security')
                    ->as('site.security.')
                    ->group(function () {
                        Route::TripwireAdminSystemRoutes();
                    });
            });


        Route::prefix('admin2/')
            ->name('api.v1.admin.')
            ->middleware('api')
            ->group(function () {
                Route::prefix('members/account')
                    ->as('members.account.')
                    ->group(function () {
                        Route::TripwireAdminRoutes();
                        Route::TripwireAdminMemberRoutes();
                    });

                Route::prefix('admins/account')
                    ->as('admins.account.')
                    ->group(function () {
                        Route::TripwireAdminAdminRoutes();
                    });
            });

        Route::TripwireResetRoutes();

    }
}

