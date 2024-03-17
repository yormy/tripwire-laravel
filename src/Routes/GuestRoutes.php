<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\ResetController;
use Yormy\TripwireLaravel\Http\Middleware\ValidateSignature;

class GuestRoutes
{
    public static function register(): void
    {
        Route::macro('TripwireResetRoutes', function (string $prefix = ''): void {
            if (config('tripwire.reset.enabled', false)) {
                Route::prefix($prefix)->name($prefix ? $prefix.'.' : '')->group(function (): void {
                    Route::prefix('')
                        ->name('tripwire.')
                        ->group(function (): void {
                            Route::prefix('guest')
                                ->name('guest.')
                                ->middleware(ValidateSignature::class)
                                ->group(function (): void {
                                    Route::get('/reset', [ResetController::class, 'reset'])->name('logs.reset');
                                });
                        });
                });
            }
        });
    }
}
