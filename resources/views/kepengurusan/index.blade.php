<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Struktur Kepengurusan HIMA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 space-y-6">
                @foreach($divisis as $divisi)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $divisi->nama_divisi }}</h3>
                    </div>
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($divisi->users as $user)
                        <li class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->global_role == 'super_admin' ? 'bg-red-100 text-red-800' : 
                                  ($user->global_role == 'pimpinan' ? 'bg-purple-100 text-purple-800' : 
                                  ($user->global_role == 'kadiv' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ strtoupper($user->global_role) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tambah Pengurus HIMA</h3>
                    
                    @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('kepengurusan.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" class="block mt-1 w-full text-sm" type="text" name="name" required />
                        </div>
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" required />
                        </div>
                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full text-sm" type="password" name="password" required />
                        </div>
                        <div>
                            <x-input-label for="divisi_id" :value="__('Divisi')" />
                            <select id="divisi_id" name="divisi_id" class="block mt-1 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                @foreach($divisis as $divisi)
                                    <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="global_role" :value="__('Jabatan (Role)')" />
                            <select id="global_role" name="global_role" class="block mt-1 w-full text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>
                                <option value="anggota">Anggota Biasa</option>
                                <option value="kadiv">Kadiv / BPH (Sekum/Bendum)</option>
                                <option value="kahim">Ketua Himpunan (Kahim)</option>
                            </select>
                        </div>
                        <div class="pt-2">
                            <x-primary-button class="w-full justify-center">
                                {{ __('Tambahkan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
