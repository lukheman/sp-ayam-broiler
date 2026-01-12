<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\UserProfile;
use App\Livewire\Admin\GejalaManagement;
use App\Livewire\Admin\PenyakitManagement;
use App\Livewire\Admin\BasisPengetahuanManagement;
use App\Livewire\Admin\RiwayatDiagnosisManagement;
use App\Livewire\Auth\Login;
use App\Livewire\LandingPage;
use App\Livewire\Diagnosis;
use App\Http\Controllers\Admin\LogoutController;

// Landing Page
Route::get('/', LandingPage::class)->name('landing');

// Diagnosis Page
Route::get('/diagnosa', Diagnosis::class)->name('diagnosa');

// Auth Routes
Route::get('/login', Login::class)->name('login');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UserManagement::class)->name('admin.users');
    Route::get('/gejala', GejalaManagement::class)->name('admin.gejala');
    Route::get('/penyakit', PenyakitManagement::class)->name('admin.penyakit');
    Route::get('/basis-pengetahuan', BasisPengetahuanManagement::class)->name('admin.basis-pengetahuan');
    Route::get('/riwayat-diagnosis', RiwayatDiagnosisManagement::class)->name('admin.riwayat-diagnosis');
    Route::get('/profile', UserProfile::class)->name('admin.profile');
    Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');
});