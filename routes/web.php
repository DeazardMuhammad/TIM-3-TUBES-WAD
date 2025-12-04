<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MessageController;

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
    
    // Claims Routes
    Route::get('/claims/create/{foundItem}', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/my-claims', [ClaimController::class, 'myClaims'])->name('claims.my-claims');
    
    // Serah Terima Routes
    Route::get('/serah-terima/{claim}', [SerahTerimaController::class, 'show'])->name('serah-terima.show');
    Route::post('/serah-terima/{claim}/upload-user', [SerahTerimaController::class, 'uploadUserBukti'])->name('serah-terima.upload-user');
    
    // Feedback Routes
    Route::get('/feedback/create/{claim}', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Messages Routes (User Chat with Admin)
    Route::get('/messages', [MessageController::class, 'userIndex'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/get', [MessageController::class, 'getMessages'])->name('messages.get');
    
    // =================
    // ADMIN ONLY ROUTES
    // =================
    
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
        Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart-data');
        
        // Kategori Management (Admin Only)
        Route::resource('kategori', KategoriController::class);
        
        // User Management Routes
        Route::resource('users', UserController::class);
        
        // Verification Routes
        Route::get('/verifikasi', [VerificationController::class, 'index'])->name('verifikasi.index');
        Route::post('/verifikasi/lost-items/{id}/approve', [VerificationController::class, 'approveLostItem'])->name('verifikasi.lost-items.approve');
        Route::post('/verifikasi/lost-items/{id}/reject', [VerificationController::class, 'rejectLostItem'])->name('verifikasi.lost-items.reject');
        Route::post('/verifikasi/found-items/{id}/approve', [VerificationController::class, 'approveFoundItem'])->name('verifikasi.found-items.approve');
        Route::post('/verifikasi/found-items/{id}/reject', [VerificationController::class, 'rejectFoundItem'])->name('verifikasi.found-items.reject');
        
        // Claims Management
        Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
        Route::get('/claims/{id}', [ClaimController::class, 'show'])->name('claims.show');
        Route::post('/claims/{id}/accept', [ClaimController::class, 'accept'])->name('claims.accept');
        Route::post('/claims/{id}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
        
        // Notes Management
        Route::get('/notes/show', [NoteController::class, 'show'])->name('notes.show');
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
        Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
        Route::get('/notes/get', [NoteController::class, 'getNotes'])->name('notes.get');
        
        // Serah Terima Management
        Route::get('/serah-terima', [SerahTerimaController::class, 'adminIndex'])->name('serah-terima.index');
        Route::get('/serah-terima/{claim}', [SerahTerimaController::class, 'adminShow'])->name('serah-terima.show');
        Route::post('/serah-terima/{claim}/upload-admin', [SerahTerimaController::class, 'uploadAdminBukti'])->name('serah-terima.upload-admin');
        
        // Feedback Management
        Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
        Route::get('/feedback/{id}', [FeedbackController::class, 'show'])->name('feedback.show');
        
        // Messages Management (Admin Chat)
        Route::get('/messages', [MessageController::class, 'adminIndex'])->name('messages.index');
        Route::get('/messages/chat/{user}', [MessageController::class, 'adminChat'])->name('messages.chat');
        
    });
    
});
