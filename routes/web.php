<?php

use App\Http\Controllers\Admin\ImpersonationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tickets\Admin\AdminTicketController;
use App\Http\Controllers\Tickets\Users\UserTicketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::Resource('tickets', UserTicketController::class)->middleware('auth')->names('user.tickets');
Route::post('tickets/{ticket}/reply', [UserTicketController::class, 'reply'])->middleware('auth')->name('user.tickets.reply');
Route::put('tickets/{ticket}/close', [UserTicketController::class, 'close'])->middleware('auth')->name('user.tickets.close');

// Rutas de Impersonación
Route::middleware(['auth'])->group(function () {
    Route::post('/impersonate/stop', [ImpersonationController::class, 'stop'])->name('impersonate.stop');
});

Route::middleware(['auth', 'role:admin|support'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('tickets', [AdminTicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [AdminTicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('tickets.reply');
    Route::put('tickets/{ticket}/status', [AdminTicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::put('tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('tickets.assign');

    // Gestión de Usuarios (Solo Admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/impersonate', [ImpersonationController::class, 'start'])->name('users.impersonate');
    });
});

Route::get('/test', function () {
    return view('test');
});

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->hasRole(['admin', 'support'])) {
            return redirect()->route('admin.tickets.index');
        }
        return redirect()->route('user.tickets.index');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::user()->hasRole(['admin', 'support'])) {
        return redirect()->route('admin.tickets.index');
    }
    return redirect()->route('user.tickets.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
