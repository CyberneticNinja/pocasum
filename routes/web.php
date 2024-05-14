<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

//Route::view('/', 'welcome');

Route::get('/', function () {
    return view('church_welcome');
})->name('home-page');

Route::get('/churches', function () {
    return view('churches.index');
})->name('churches')->middleware(['auth', 'verified']);

Route::get('/groups', function () {
    return view('groups.index');
})->name('groups')->middleware(['auth', 'verified']);

Route::get('/users', function () {
    return view('users.index');
})->name('users')->middleware(['auth', 'verified']);

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('admin/dashboard', 'dashboard.admin-dashboard')
    ->middleware(['auth', 'verified'])
    ->name('admin-dashboard');

Route::view('user/dashboard', 'dashboard.users-dashboard')
    ->middleware(['auth', 'verified'])
    ->name('users-dashboard');

Route::view('group-leader/dashboard', 'dashboard.group-leader-dashboard')
    ->middleware(['auth', 'verified'])
    ->name('group-leader-dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

require __DIR__.'/auth.php';
