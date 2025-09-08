<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;

// =================
// PUBLIC ROUTES
// =================

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');

// =================
// PROTECTED ROUTES (Authenticated Users)
// =================

Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');
    
    // Dashboard Routes - Auto redirect based on role
    Route::get('/dashboard', function() {
        if (Auth::user()->isAdmin()) {
            return redirect('/admin/dashboard');
        }
        return app(App\Http\Controllers\DashboardController::class)->index();
    })->name('dashboard');
    
    // Lost Items Routes - Accessible by all authenticated users
    Route::resource('lost-items', LostItemController::class);
    Route::patch('/lost-items/{lostItem}/status', [LostItemController::class, 'updateStatus'])->name('lost-items.update-status');
    
    // Found Items Routes - Accessible by all authenticated users  
    Route::resource('found-items', FoundItemController::class);
    Route::patch('/found-items/{foundItem}/status', [FoundItemController::class, 'updateStatus'])->name('found-items.update-status');
    
    // =================
    // ADMIN ONLY ROUTES
    // =================
    
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        
        // Kategori Management (Admin Only)
        Route::resource('kategori', KategoriController::class);
        
        // User Management Routes
        Route::resource('users', UserController::class);
        
    });
    
});
