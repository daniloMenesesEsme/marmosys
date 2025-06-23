<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas para API de lojas e contas correntes
Route::get('/stores/list', 'App\Http\Controllers\Api\StoreController@list')->name('api.stores.list');
Route::get('/current-accounts/by-store', 'App\Http\Controllers\Api\CurrentAccountController@getByStore')->name('api.current-accounts.by-store');

Route::get('/cities/{state}', [LocationController::class, 'getCities']);
Route::get('/neighborhoods/{state}/{city}', [LocationController::class, 'getNeighborhoods']);
Route::get('/geocode/{state}/{city}/{neighborhood}', [LocationController::class, 'getGeocode']);
