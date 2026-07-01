<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect($this->resolveRedirectPath());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Determine the redirect path based on the user's role.
     * 
     * Priority:
     * 1. Kepanitiaan roles (Ketupel/Inti → CO → Anggota)
     * 2. Kepengurusan roles (super_admin/kahim → kadiv → anggota)
     */
    protected function resolveRedirectPath(): string
    {
        $user = Auth::user();

        // 1. Cek role kepanitiaan aktif — prioritas: Ketupel > CO > Anggota
        $activeEvents = $user->getActiveEvents();
        if ($activeEvents->isNotEmpty()) {
            $ketupelRole = $activeEvents->first(fn($c) => in_array($c->role->slug, ['ketua-pelaksana', 'wakil-ketua-pelaksana']));
            if ($ketupelRole) {
                return route('kepanitiaan.ketupel.dashboard', ['event' => $ketupelRole->event_id], false);
            }

            $coRole = $activeEvents->first(fn($c) => $c->role->slug === 'co-divisi');
            if ($coRole) {
                return route('kepanitiaan.co.dashboard', ['event' => $coRole->event_id, 'division' => $coRole->event_division_id], false);
            }

            $anggotaRole = $activeEvents->first(fn($c) => $c->role->slug === 'anggota');
            if ($anggotaRole) {
                return route('kepanitiaan.anggota.dashboard', ['event' => $anggotaRole->event_id, 'division' => $anggotaRole->event_division_id], false);
            }
        }

        // 2. Fallback ke dashboard kepengurusan berdasarkan global_role
        return route('dashboard', absolute: false);
    }
}
