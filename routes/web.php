<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::get('/tentang', function () {
    return view('about');
})->name('about');

Route::get('/berita', function () {
    return view('berita');
})->name('berita');

Route::get('/galeri', function () {
    return view('galeri');
})->name('galeri');

Route::get('/struktur', function () {
    return redirect()->route('home'); // Redirect or keep as index
})->name('structure');

Route::get('/struktur/{slug}', function ($slug) {
    $divisi = \App\Models\Division::with('users')->where('slug', $slug)->firstOrFail();
    return view('division', compact('divisi'));
})->name('division.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
