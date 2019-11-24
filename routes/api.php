<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth', 'AuthController@auth' )->name('auth');

Route::middleware(['valid.token'])->group(function () {

    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/detail/{sku}', 'CarsController@detail' );
        Route::get('/{type}/{brand?}/{model?}', 'CarsController@filter' );
    });


});
