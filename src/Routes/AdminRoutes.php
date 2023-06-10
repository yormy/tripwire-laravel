<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;

class AdminRoutes
{
    public static function register()
    {
        Route::macro('TripwireAdminRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix . "." : '')->group(function () {

                Route::prefix('')
                    ->name('guest.')
                    ->group(function () {

                        Route::prefix('registration')
                            ->name('xxx.')
//                            ->middleware("guest")
                            ->group(function () {

                                Route::get('/', [RegistrationController::class, 'create'])->name('create');
                            });
                    });
            });
        });
    }
}
