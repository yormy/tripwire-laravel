<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\ResetController;

class GuestRoutes
{
    public static function register()
    {
        if (config('tripwire.reset.allowed')) {
            Route::macro('TripwireResetRoutes', function (string $prefix = '') {
                Route::prefix($prefix)->name($prefix ? $prefix . "." : '')->group(function () {

                    Route::prefix('')
                        ->name('tripwire.')
                        ->group(function () {

                            Route::prefix('guest')
                                ->name('guest.')
//                            ->middleware("guest") //todo signed
                                ->group(function () {
                                    Route::get('/reset', [ResetController::class, 'reset'])->name('logs.reset');
                                });
                        });
                });
            });
        }

    }
}
