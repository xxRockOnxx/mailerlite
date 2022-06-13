<?php

use App\Http\Controllers\FieldController;
use App\Http\Controllers\SubscribersController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('/subscribers')
    ->controller(SubscribersController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{subscriber}', 'show');
        Route::put('/{subscriber}', 'update');
        Route::delete('/{subscriber}', 'destroy');

        Route::get('/{subscriber}/fields', 'listFields');
        Route::post('/{subscriber}/fields', 'addField');
        Route::put('/{subscriber}/fields', 'setFields');
    });

Route::prefix('/fields')
    ->controller(FieldController::class)
    ->group(function () {
        Route::get('/{field}', 'show');
        Route::put('/{field}', 'update');
        Route::delete('/{field}', 'destroy');
    });
