<?php

use Illuminate\Http\Request;
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

Route::middleware([
    'auth',
])->group(function () {

    Route::get('entrees/{id}', '\Milkhan\FiverMetricTool\Http\Controllers\InitialController@getEntrees');
    // Get all entrees for an event


    Route::get('get-entree-count/{id}', '\Milkhan\FiverMetricTool\Http\Controllers\InitialController@getEntreeCount');
    // Get entreeStarted count for an entree
});