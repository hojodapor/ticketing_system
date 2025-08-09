<?php

use App\Http\Controllers\ticketController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/tickets', [ticketController::class, 'showForm'])
        ->name('ticket.form');
Route::post('/tickets', [ticketController::class, 'processForm'])
        ->name('ticket.process');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/tickets', [AdminTicketController::class, 'index'])->name('admin.tickets.index');
        Route::get('/tickets/{ticket}', [AdminTicketController::class, 'show'])->name('admin.tickets.show');
        Route::put('/tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('admin.tickets.updateStatus');
        Route::put('/tickets/{ticket}/priority', [AdminTicketController::class, 'updatePriority'])->name('admin.tickets.updatePriority');
    });
});
