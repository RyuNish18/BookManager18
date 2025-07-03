<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard route (handles both teacher and student views)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Book management routes (for teachers)
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/books/assign', [BookController::class, 'showAssignForm'])->name('books.assign');
Route::post('/books/assign', [BookController::class, 'assign'])->name('books.assign.store');
