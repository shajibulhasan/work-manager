<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Deposits - Full CRUD
    Route::resource('deposits', DepositController::class);
    
    // Expenses - Full CRUD
    Route::resource('expenses', ExpenseController::class);
    
    // Categories - Full CRUD
    Route::resource('categories', CategoryController::class);
    
    // Additional category routes
    Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
         ->name('categories.toggle-status');
    Route::get('/api/categories/{type}', [CategoryController::class, 'getByType'])
         ->name('categories.by-type');
    
    // Tasks - Full CRUD
    Route::resource('tasks', TaskController::class);
    
    // Task quick actions
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
         ->name('tasks.update-status');
});