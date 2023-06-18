<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\ResetController;
use Yormy\TripwireLaravel\Http\Middleware\ValidateSignature;

class GuestRoutes
{
    public static function register()
    {
        Route::macro('TripwireResetRoutes', function (string $prefix = '') {
            if (config('tripwire.reset.enabled')) {
                Route::prefix($prefix)->name($prefix ? $prefix.'.' : '')->group(function () {

                    Route::prefix('')
                        ->name('tripwire.')
                        ->group(function () {

                            Route::prefix('guest')
                                ->name('guest.')
                                ->middleware(ValidateSignature::class)
                                ->group(function () {
                                    Route::get('/reset', [ResetController::class, 'reset'])->name('logs.reset');
                                });
                        });
                });
            }
        });
    }
}
