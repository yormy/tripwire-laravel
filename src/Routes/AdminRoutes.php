<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\BlockController;
use Yormy\TripwireLaravel\Http\Controllers\LogController;
use Yormy\TripwireLaravel\Http\Controllers\ResetController;

class AdminRoutes
{
    public static function register(): void
    {
        Route::macro('TripwireAdminRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix.'.' : '')->group(function () {

                Route::prefix('tripwire/')
                    ->name('tripwire.')
                    ->group(function () {
//                            Route::get('/reset-key', [ResetController::class, 'getKey'])->name('reset-key');
//                            Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');
                            Route::get('/{member_xid}/logs', [LogController::class, 'index'])->name('logs.index');
                            Route::get('/{member_xid}/blocks', [BlockController::class, 'index'])->name('blocks.index');
                    });
            });
        });
    }
}
