<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Proker Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-8 border border-gray-100 dark:border-gray-700">
                
                <form action="{{ route('prokers.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="nama_proker" :value="__('Nama Program Kerja')" />
                        <x-text-input id="nama_proker" class="block mt-1 w-full" type="text" name="nama_proker" :value="old('nama_proker')" required autofocus />
                        <x-input-error :messages="$errors->get('nama_proker')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="divisi_id" :value="__('Divisi Pelaksana')" />
                        <select id="divisi_id" name="divisi_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisis as $divisi)
                                <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                    {{ $divisi->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('divisi_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Tanggal Selesai')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="budget_plan" :value="__('Rencana Anggaran (Rp)')" />
                        <x-text-input id="budget_plan" class="block mt-1 w-full" type="number" name="budget_plan" :value="old('budget_plan')" required min="0" />
                        <x-input-error :messages="$errors->get('budget_plan')" class="mt-2" />
                    </div>

                    <div class="pt-4 flex items-center justify-end">
                        <a href="{{ route('prokers.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Simpan Proker') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
