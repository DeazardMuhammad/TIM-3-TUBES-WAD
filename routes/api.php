<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;

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

// =================
// PUBLIC API ROUTES
// =================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Test routes (public access) - hanya untuk development
Route::get('/test/lost-items', [LostItemController::class, 'index']);
Route::get('/test/found-items', [FoundItemController::class, 'index']);

// =================
// PROTECTED API ROUTES (Sanctum)
// =================

Route::middleware(['auth:sanctum'])->group(function () {
    
    // User info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Lost Items API
    Route::apiResource('lost-items', LostItemController::class);
    Route::patch('/lost-items/{lostItem}/status', [LostItemController::class, 'updateStatus']);
    
    // Found Items API
    Route::apiResource('found-items', FoundItemController::class);
    Route::patch('/found-items/{foundItem}/status', [FoundItemController::class, 'updateStatus']);
    
}); 