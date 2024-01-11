<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\Admins\AdminBlockController;
use Yormy\TripwireLaravel\Http\Controllers\Admins\AdminLogController;
use Yormy\TripwireLaravel\Http\Controllers\System\SystemLogController;
use Yormy\TripwireLaravel\Http\Controllers\System\SystemBlockController;
use Yormy\TripwireLaravel\Http\Controllers\BlockController;
use Yormy\TripwireLaravel\Http\Controllers\LogController;
use Yormy\TripwireLaravel\Http\Controllers\Members\MemberBlockController;
use Yormy\TripwireLaravel\Http\Controllers\Members\MemberLogController;
use Yormy\TripwireLaravel\Http\Controllers\ResetController;

class AdminRoutes
{
    public static function register(): void
    {
        Route::macro('TripwireAdminSystemRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix . '.' : '')->group(function () {

                Route::prefix('tripwire/')
                    ->name('tripwire.')
                    ->group(function () {
                        Route::get('/blocks', [SystemBlockController::class, 'index'])->name('blocks.index');
                        Route::get('/logs', [SystemLogController::class, 'index'])->name('logs.index');

                        Route::prefix('/blocks/{block_xid}')
                            ->name('blocks.')
                            ->group(function () {
                                Route::get('', [BlockController::class, 'show'])->name('show');
                                Route::get('/logs', [LogController::class, 'indexForBlock'])->name('logs.index');

                                Route::delete('', [BlockController::class, 'delete'])->name('delete');
                                Route::patch('', [BlockController::class, 'unblock'])->name('unblock');
                                Route::patch('/persist', [BlockController::class, 'persist'])->name('persist');
                                Route::patch('/unpersist', [BlockController::class, 'unpersist'])->name('unpersist');
                            });
                    });
            });
        });


        Route::macro('TripwireAdminRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix.'.' : '')->group(function () {

                Route::prefix('tripwire/')
                    ->name('tripwire.')
                    ->group(function () {
                        // only for user X
                        Route::get('/reset-key', [ResetController::class, 'getKey'])->name('reset-key');
                    });
            });
        });

        Route::macro('TripwireAdminMemberRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix . '.' : '')->group(function () {

                Route::prefix('tripwire/')
                    ->name('tripwire.')
                    ->group(function () {
                        Route::prefix('/{member_xid}')
                            ->name('')
                            ->group(function () {
                                Route::get('/logs', [MemberLogController::class, 'index'])->name('logs.index');
                                Route::get('/blocks', [MemberBlockController::class, 'index'])->name('blocks.index');
                            });
                    });
            });
        });


        Route::macro('TripwireAdminAdminRoutes', function (string $prefix = '') {
            Route::prefix($prefix)->name($prefix ? $prefix . '.' : '')->group(function () {

                Route::prefix('tripwire/')
                    ->name('tripwire.')
                    ->group(function () {
                        Route::prefix('/{admin_xid}')
                            ->name('')
                            ->group(function () {
                                Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
                                Route::get('/blocks', [AdminBlockController::class, 'index'])->name('blocks.index');
                            });
                    });
            });
        });

    }
}
