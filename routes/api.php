<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SpotsController;
use App\Http\Controllers\SpotVaccinesController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\VaccinesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function() {
        Route::post('/login', [AuthController::class,'login']);
        Route::post('/logout', [AuthController::class,'logout']);
    });
});
Route::group(['prefix' => 'v1'], function(){
    Route::post('/consultation', [ConsultationController::class, 'store']);
    Route::get('/consultation', [ConsultationController::class, 'showConsultation']);
    Route::get('/spots', [SpotsController::class, 'indexSpots']);
    Route::get('/SpotVaccines', [SpotVaccinesController::class, 'SpotVaccines']);
    Route::get('/spots/{id}', [SpotsController::class, 'showSpots']);
    Route::post('/Vaccination', [VaccinationController::class, 'storeVaccination']);
    Route::get('/Vaccination', [VaccinationController::class, 'showVaccination']);

});

