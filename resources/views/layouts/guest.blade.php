<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>HIMASI Project Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Full-Screen Background -->
    <div class="min-h-screen relative flex flex-col sm:items-center sm:justify-center overflow-hidden bg-slate-900">

        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('pengurus_himasi.png') }}" class="w-full h-full object-cover object-center" alt="HIMASI">
            <div class="absolute inset-0 bg-slate-900/80"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/60 via-slate-900/80 to-slate-900/90"></div>
        </div>

        <!-- Decorative Glow Blobs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none z-0"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none z-0"></div>

        <!-- Header Content (Welcome Text) -->
        <div class="relative z-10 w-full max-w-xl px-6 pt-12 pb-6 sm:py-8 text-center flex-grow flex flex-col justify-end fade-up">
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight mb-2">
                Masuk ke dalam <span class="text-indigo-400">Sistem</span>
            </h1>
            <p class="text-indigo-200 text-sm sm:text-base font-medium">Silakan masuk menggunakan akun HIMASI Anda untuk melanjutkan.</p>
        </div>

        <!-- Login Card -->
        <div class="relative z-10 w-full sm:max-w-xl mt-auto sm:mt-0 flex-grow sm:flex-grow-0 flex flex-col justify-end sm:justify-center">
            
            <!-- Card -->
            <div class="bg-white/95 backdrop-blur-xl rounded-t-[2.5rem] sm:rounded-3xl shadow-[0_-15px_40px_rgba(0,0,0,0.3)] sm:shadow-2xl sm:shadow-black/40 sm:border sm:border-white/20 px-6 py-10 sm:p-10 fade-up delay-100 w-full">
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <!-- Form -->
                {{ $slot }}

                <!-- Footer Links -->
                <div class="mt-10 pt-8 border-t border-slate-200 w-full flex flex-col items-center justify-center text-center gap-3">
                    <p class="text-xs text-slate-500">&copy; {{ date('Y') }} HIMASI Universitas Jambi. All rights reserved.</p>
                </div>

            </div>

        </div>
    </div>
</body>

</html>