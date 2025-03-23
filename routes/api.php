<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas para API de lojas e contas correntes
Route::get('/stores/list', 'App\Http\Controllers\Api\StoreController@list')->name('api.stores.list');
Route::get('/current-accounts/by-store', 'App\Http\Controllers\Api\CurrentAccountController@getByStore')->name('api.current-accounts.by-store');
