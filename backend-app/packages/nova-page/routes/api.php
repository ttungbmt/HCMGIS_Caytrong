<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::namespace('\Larabase\NovaPage\Http\Controllers')->group(function () {
    Route::prefix('nova-vendor/nova-page')->group(function () {
        Route::get('/page', 'PageController@get')->name('nova-page.get');
        Route::post('/page', 'PageController@post')->name('nova-page.post');
    });
});
