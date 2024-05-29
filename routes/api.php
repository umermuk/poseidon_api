<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    BuildingTypeController,
    EstimateController,
    RoofController,
    SteepRoofController,
};
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

// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/roof', [RoofController::class, 'index']);
Route::get('/steep-roof', [SteepRoofController::class, 'index']);
Route::get('/building-type', [BuildingTypeController::class, 'index']);
Route::post('/estimate', [EstimateController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [AuthController::class, 'user']);
    Route::apiResources([
        'roof' => RoofController::class,
        'steep-roof' => SteepRoofController::class,
        'building-type' => BuildingTypeController::class,
    ],[
        'except' => ['index']
    ]);
    Route::apiResources([
        'estimate' => EstimateController::class,
    ],[
        'except' => ['store']
    ]);
});
