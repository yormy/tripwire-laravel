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
              //  Route::PromocodesApiV1();
            });
    }
}
