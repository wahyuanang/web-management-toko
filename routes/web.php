<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Karyawan\DashboardController;
use App\Http\Controllers\Karyawan\AssignmentController;
use App\Http\Controllers\Karyawan\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// Guest Routes (Not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect after login based on role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->hasRole('admin')) {
            return redirect('/admin');
        }
        if (Auth::user()->hasRole('karyawan')) {
            return redirect()->route('karyawan.dashboard');
        }
        return redirect('/');
    })->name('dashboard');
});

// Karyawan Routes
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assignments - Read Only
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');

    // Reports - Full CRUD
    Route::resource('reports', ReportController::class);

    // API untuk get assignment details
    Route::get('/api/assignments/{id}', [\App\Http\Controllers\Api\AssignmentController::class, 'show'])->name('api.assignments.show');
});
