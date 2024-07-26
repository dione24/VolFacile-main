<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/flight-details/{flightNumber}', [FlightController::class, 'showFlightDetails']);