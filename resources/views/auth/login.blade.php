<x-guest-layout>
    <!-- Logo -->
    <div class="mb-8 flex justify-center">
        <a href="/">
            <img src="{{ asset('logo_himasi.png') }}" alt="HIMASI" class="h-16 w-auto drop-shadow-sm">
        </a>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-800 mb-2">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input
                    id="email"
                    class="block w-full rounded-2xl border border-indigo-400 focus:border-indigo-600 focus:ring focus:ring-indigo-500/20 pl-11 pr-4 py-3.5 text-sm text-slate-800 transition-all bg-indigo-50/30 shadow-sm placeholder-slate-400"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="admin@himasi.unja.ac.id"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-sm font-bold text-slate-800">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input
                    id="password"
                    class="block w-full rounded-2xl border border-slate-200 focus:border-indigo-600 focus:ring focus:ring-indigo-500/20 pl-11 pr-12 py-3.5 text-sm text-slate-800 transition-all bg-slate-50 shadow-sm placeholder-slate-400"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer gap-2">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4"
                    name="remember"
                >
                <span class="text-sm font-medium text-slate-600">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 px-4 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/30 transition-all flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Masuk Sekarang
            </button>
        </div>
        <div class="pt-2">
            <a href="/" class="mt-5 inline-flex items-center justify-center w-full gap-1.5 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Kembali ke Beranda</span>
                <svg class="w-4 h-4 invisible" fill="none" viewBox="0 0 24 24"></svg>
            </a>
        </div>
    </form>
</x-guest-layout>
