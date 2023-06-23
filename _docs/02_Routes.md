# Admin routes registration
In your routes file specify the prefix/middelsware/group and register the tripwire routes
Route::TripwireAdminRoutes();
This way you can specify where the routes are in your path and namespaces and what middleware you want to apply

These routes should be only accessible by an admin
```
        Route::prefix('')
            ->middleware('api')
            ->group(function () {
                    Route::TripwireAdminRoutes();
            });
```
