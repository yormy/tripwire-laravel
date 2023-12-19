<?php

namespace Yormy\TripwireLaravel\Routes;

use Illuminate\Support\Facades\Route;
use Yormy\TripwireLaravel\Http\Controllers\Admins\AdminBlockController;
use Yormy\TripwireLaravel\Http\Controllers\Admins\AdminLogController;
use Yormy\TripwireLaravel\Http\Controllers\BlockController;
use Yormy\TripwireLaravel\Http\Controllers\LogController;
use Yormy\TripwireLaravel\Http\Controllers\Members\MemberBlockController;
use Yormy\TripwireLaravel\Http\Controllers\Members\MemberLogController;
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
                        // only for user X
                        Route::get('/reset-key', [ResetController::class, 'getKey'])->name('reset-key');
                        //                            Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');

//                            Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index'); // all blocks for system managemetn

                            // get details of blocks
                            Route::get('/blocks/{block_xid}', [BlockController::class, 'show'])->name('blocks.show');
                            Route::get('/blocks/{block_xid}/logs', [LogController::class, 'indexForBlock'])->name('blocks.logs.index');

                            Route::delete('/blocks/{block_xid}', [BlockController::class, 'delete'])->name('blocks.delete');
                            Route::patch('/blocks/{block_xid}', [BlockController::class, 'unblock'])->name('blocks.unblock');
                            Route::patch('/blocks/{block_xid}/persist', [BlockController::class, 'persist'])->name('blocks.persist');
                            Route::patch('/blocks/{block_xid}/unpersist', [BlockController::class, 'unpersist'])->name('blocks.unpersist');
//
//                            Route::get('/logs', [LogController::class, 'index'])->name('logs.index'); // all logs for system managemetn


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
