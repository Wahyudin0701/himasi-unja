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
            
    $pembinaDivision = \App\Models\Kepengurusan\Division::where('type', 'pembina')
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition'])
            ->first();
            
    $dpDivision = \App\Models\Kepengurusan\Division::where('type', 'dp')
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition'])
            ->first();

    $resolveAvatar = function($avatar) {
        if (!$avatar) return null;
        if (file_exists(public_path(ltrim($avatar, '/')))) return asset(ltrim($avatar, '/'));
        if (file_exists(public_path('storage/' . ltrim($avatar, '/')))) return asset('storage/' . ltrim($avatar, '/'));
        return null;
    };

    if ($bphDivision) {
        $bphDivision->users = $bphDivision->members->map(function($m) use ($resolveAvatar) {
            $u = clone $m->user;
            $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
            $u->avatar_url = $resolveAvatar($u->avatar);
            return $u;
        });
    }
    
    if ($pembinaDivision) {
        $pembinaDivision->users = $pembinaDivision->members->map(function($m) use ($resolveAvatar) {
            $u = clone $m->user;
            $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
            $u->avatar_url = $resolveAvatar($u->avatar);
            return $u;
        });
    }
    
    if ($dpDivision) {
        $dpDivision->users = $dpDivision->members->map(function($m) use ($resolveAvatar) {
            $u = clone $m->user;
            $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
            $u->avatar_url = $resolveAvatar($u->avatar);
            return $u;
        });
    }

    $divisions = \App\Models\Kepengurusan\Division::where('type', 'divisi')
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition'])
            ->get();
            
    foreach ($divisions as $div) {
        $div->users = $div->members->map(function($m) use ($resolveAvatar) {
            $u = clone $m->user;
            $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
            $u->avatar_url = $resolveAvatar($u->avatar);
            return $u;
        });
    }
            
    return view('public.structure', compact('bphDivision', 'divisions', 'pembinaDivision', 'dpDivision'));
})->name('structure');

Route::get('/division/{slug}', function ($slug) {
    $divisi = \App\Models\Kepengurusan\Division::where('slug', $slug)
            ->whereHas('period', function($q) { $q->where('is_active', true); })
            ->with(['members.user', 'members.orgPosition', 'workPrograms'])
            ->firstOrFail();

    $resolveAvatar = function($avatar) {
        if (!$avatar) return null;
        if (file_exists(public_path(ltrim($avatar, '/')))) return asset(ltrim($avatar, '/'));
        if (file_exists(public_path('storage/' . ltrim($avatar, '/')))) return asset('storage/' . ltrim($avatar, '/'));
        return null;
    };

    $divisi->users = $divisi->members->map(function($m) use ($resolveAvatar) {
        $u = clone $m->user;
        $u->position_title = $m->position_title ?: ($m->orgPosition ? $m->orgPosition->name : 'Anggota');
        $u->avatar_url = $resolveAvatar($u->avatar);
        return $u;
    });

    return view('public.division', compact('divisi'));
})->name('division.show');

Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    
    // Pembina & DP → Dashboard overview
    if (in_array($user->global_role, ['pembina', 'dp'])) {
        return redirect()->route('kepengurusan.sekretaris.dashboard');
    }

    // Super Admin Dashboard
    if ($user->global_role === 'super_admin') {
        return redirect()->route('super_admin.dashboard');
    }

    // Kepengurusan Dashboards
    if (in_array($user->global_role, ['kahim', 'wakahim', 'sekretaris', 'bendahara'])) {
        return redirect()->route('kepengurusan.sekretaris.dashboard');
    }
    
    if ($user->global_role === 'kadiv') {
        return redirect()->route('kepengurusan.kadiv.dashboard');
    }

    if ($user->global_role === 'anggota') {
        return redirect()->route('kepengurusan.anggota.dashboard');
    }

    // Kepanitiaan Dashboards
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
