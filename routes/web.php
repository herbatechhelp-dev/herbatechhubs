<?php

use App\Livewire\ShortcutAdminPanel;
use App\Livewire\ShortcutDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', ShortcutDashboard::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', ShortcutAdminPanel::class)->name('dashboard');
    Route::redirect('admin/shortcuts', 'dashboard');
});

require __DIR__.'/settings.php';
