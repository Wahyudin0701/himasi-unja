<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen bg-gray-50 overflow-hidden font-sans">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Topbar -->
                <header class="flex-shrink-0 bg-white/80 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 transition-colors duration-300">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden hover:text-indigo-600 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        
                        @isset($header)
                            <!-- Page Title slot rendered from view -->
                            <div class="hidden sm:block">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    <!-- Settings Dropdown -->
                    <div class="flex items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none transition duration-150 ease-in-out">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 border border-indigo-200 flex items-center justify-center text-indigo-700 font-bold mr-3 shadow-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="hidden md:block">{{ Auth::user()->name }}</div>
                                    <div class="ms-1 hidden md:block text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                            class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                @isset($header)
                    <!-- Page Title slot rendered from view on Mobile -->
                    <div class="sm:hidden px-4 py-4 bg-white/80 backdrop-blur border-b border-gray-200">
                        {{ $header }}
                    </div>
                @endisset

                <!-- Main Content Scrollable -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 transition-colors duration-300">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
