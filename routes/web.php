<?php

use App\Http\Controllers\Flights;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/api/flights', [Flights::class, 'create']);