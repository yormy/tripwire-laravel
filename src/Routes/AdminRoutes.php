<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\LogController;

class AdminRoutes
{
    public static function register()
    {
        Route::macro('TripwireAdminRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix . "." : '')->group(function () {

                Route::prefix('')
                    ->name('tripwire.')
                    ->group(function () {

                        Route::prefix('admin/logs')
                            ->name('admin.logs.')
//                            ->middleware("guest")
                            ->group(function () {

                                Route::get('/', [LogController::class, 'index'])->name('indexx');
                            });
                    });
            });
        });
    }
}
