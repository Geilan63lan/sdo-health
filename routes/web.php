<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::view('/pending-approval', 'auth.pending-approval')->name('pending-approval');
});

require __DIR__.'/settings.php';
