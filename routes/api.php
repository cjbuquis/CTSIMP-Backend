<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlaceController;
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

// Non-authenticated APIs
Route::post('/register', [UserController::class, 'store']); // User registration (Non-authenticated)
Route::post('/login', [UserController::class, 'login']); // User login (Non-authenticated)

// Authenticated APIs
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user(); // Get authenticated user (Authenticated)
    });

    Route::post('/change-password', [UserController::class, 'changePassword']); // Change user password (Authenticated)

    // User management
    Route::get('/users', [UserController::class, 'index']); // Get all users (Authenticated)
    Route::get('/pendingusers', [UserController::class, 'pending']); // Get all pending users (Authenticated)
    Route::get('/approvedusers', [UserController::class, 'approved']); // Get all approved users (Authenticated)
    Route::put('/users/{user}', [UserController::class, 'update']); // Update a user (Authenticated)
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // Delete a user (Authenticated)
    Route::put('/users/{id}/status', [UserController::class, 'updateStatus']); // Update the status of a user (Authenticated)

    // Place management
    Route::get('/places', [PlaceController::class, 'index']);  // Get all places (Authenticated)
    Route::post('/places', [PlaceController::class, 'store']); // Create a new place (Authenticated)
    Route::get('/approvedplaces', [PlaceController::class, 'carousel']); // Get all approved places (Authenticated)
    Route::get('/pending', [PlaceController::class, 'pending']); // Get all pending places (Authenticated)
    Route::get('/places/{place}', [PlaceController::class, 'show']); // Get a specific place (Authenticated)
    Route::put('/places/{place}', [PlaceController::class, 'update']); // Update a place (Authenticated)
    Route::delete('/places/{place}', [PlaceController::class, 'destroy']); // Delete a place (Authenticated)
    Route::put('/places/{id}/status', [PlaceController::class, 'updateStatus']); // Update the status of a place (Authenticated)

    // Get places submitted by a specific user
    Route::get('/users/{user}/places', [PlaceController::class, 'getPlacesByUser']); // Get places by user (Authenticated)
});
