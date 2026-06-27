<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('public.home');
})->name('home');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

Route::get('/divisions', function () {
    return redirect()->route('structure');
})->name('divisions');

Route::get('/galeri', function () {
    return view('public.galeri');
})->name('galeri');

Route::get('/berita', function () {
    return view('public.berita');
})->name('berita');

Route::get('/struktur', function () {
    // Akan diupdate nanti setelah controller ada
    $bphDivision = \App\Models\Kepengurusan\Division::where('type', 'bph')
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition'])
            ->first();
            
    if ($bphDivision) {
        $bphDivision->users = $bphDivision->members->map(function($m) {
            $u = clone $m->user;
            $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
            return $u;
        });
    }
            
    $divisions = \App\Models\Kepengurusan\Division::where('type', 'divisi')
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition'])
            ->get();
            
    foreach ($divisions as $div) {
        $div->users = $div->members->map(function($m) {
            $u = clone $m->user;
            $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
            return $u;
        });
    }
            
    return view('public.structure', compact('bphDivision', 'divisions'));
})->name('structure');

Route::get('/division/{slug}', function ($slug) {
    $divisi = \App\Models\Kepengurusan\Division::where('slug', $slug)
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition', 'workPrograms'])
            ->firstOrFail();

    $divisi->users = $divisi->members->map(function($m) {
        $u = clone $m->user;
        $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
        return $u;
    });

    return view('public.division', compact('divisi'));
})->name('division.show');

Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    
    if ($user->hasActiveKetupelRole()) {
        return redirect()->route('kepanitiaan.ketupel.dashboard');
    }
    if ($user->hasActiveCORole()) {
        return redirect()->route('kepanitiaan.co.dashboard');
    }
    if ($user->hasActiveAnggotaRole()) {
        return redirect()->route('kepanitiaan.anggota.dashboard');
    }
    
    // Default fallback if no specific dashboard
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// New Architecture Routes
require __DIR__.'/kepengurusan.php';
require __DIR__.'/kepanitiaan.php';
require __DIR__.'/progress-report.php';
require __DIR__.'/arsip.php';
