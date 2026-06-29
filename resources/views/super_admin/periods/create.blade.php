@extends('layouts.dashboard')

@section('title', 'Pergantian Kepengurusan')

@section('breadcrumbs')
    <a href="{{ route('super_admin.dashboard') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Super Admin</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.periods.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Manajemen Periode</a>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <span class="text-slate-700">Periode Baru</span>
@endsection

@section('content')
<div class="w-full">
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">Pergantian Kepengurusan Baru</h1>
        <p class="text-sm text-slate-500 mt-2 leading-relaxed">
            Mulai periode kepengurusan baru. Sistem akan secara otomatis mengarsipkan data lama, membuat *placeholder* (jabatan kosong sementara) untuk Pimpinan (BPH & Kadiv) yang baru, dan mereset anggota.
        </p>
    </div>

    @if($currentPeriod)
    <div class="mb-8 bg-red-50 border-2 border-red-200 rounded-2xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0 shadow-sm border border-red-200">
                <i class="ph-fill ph-warning-octagon text-2xl animate-pulse"></i>
            </div>
            <div>
                <h3 class="font-black text-red-900 text-lg uppercase tracking-wide">Tindakan Sensitif & Permanen</h3>
                <p class="text-sm text-red-700 mt-2 leading-relaxed font-medium">
                    Periode aktif saat ini adalah <strong>{{ $currentPeriod->name }}</strong>. Mengeksekusi transisi periode akan mematikan seluruh aktivitas sistem di periode saat ini secara permanen.
                </p>
                <div class="mt-4 bg-white/50 rounded-xl p-4 border border-red-100">
                    <p class="text-sm font-bold text-red-900 mb-2">Hal berikut akan terjadi secara otomatis dan menjadi arsip sejarah:</p>
                    <ul class="list-disc pl-5 space-y-1.5 text-sm text-red-800">
                        <li><strong>Data Pengurus:</strong> Semua akun pengurus (BPH, Kadiv, Anggota) akan dinonaktifkan dan tidak bisa lagi login (kecuali Anda).</li>
                        <li><strong>Program Kerja & Event:</strong> Seluruh Proker dan Event Kepanitiaan akan diarsipkan dan ditutup.</li>
                        <li><strong>Tugas & Laporan:</strong> Seluruh proses manajemen tugas, laporan progres, dan administrasi divisi akan dihentikan.</li>
                    </ul>
                </div>
                <p class="text-xs text-red-600 mt-4 font-bold italic">
                    * Pastikan seluruh laporan dan tugas periode ini telah benar-benar rampung sebelum menekan tombol eksekusi.
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('super_admin.periods.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ showModal: false, confirmed: false }">
        @csrf
        
        <div class="p-6 sm:p-8">
            <div class="space-y-6">
                
                {{-- Nama Periode Otomatis --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Periode Baru</label>
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl p-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-calendar-star text-lg"></i>
                        </div>
                        <div>
                            <div class="text-base font-black text-slate-900">{{ $nextPeriodName }}</div>
                            <p class="text-xs font-medium text-slate-500">Dihasilkan otomatis oleh sistem berdasarkan tahun atau periode sebelumnya.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-slate-50 px-6 py-4 sm:px-8 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('super_admin.periods.index') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">Batal</a>
            <button type="button" @click="showModal = true" class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-brand-500/20">
                Eksekusi Transisi Periode
            </button>
        </div>

        <!-- Modal Konfirmasi -->
        <div x-cloak x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="showModal = false" 
                 x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <i class="ph-fill ph-warning-octagon text-2xl text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">Konfirmasi Eksekusi</h3>
                    </div>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                        Apakah Anda benar-benar yakin ingin mengeksekusi transisi periode? Tindakan ini <strong>permanen</strong> dan semua data aktif akan diarsipkan.
                    </p>
                    
                    <label class="flex items-start gap-3 p-4 bg-red-50 border-2 border-red-100 rounded-xl cursor-pointer hover:bg-red-100/50 transition-colors">
                        <input type="checkbox" x-model="confirmed" class="mt-0.5 rounded border-red-300 text-red-600 focus:ring-red-500 w-4 h-4 shrink-0 transition-colors">
                        <span class="text-sm text-red-900 font-bold leading-snug">Saya mengerti dan menyetujui bahwa tindakan ini tidak dapat dibatalkan.</span>
                    </label>
                </div>
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" @click="showModal = false; confirmed = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">Batal</button>
                    <button type="submit" :disabled="!confirmed" :class="!confirmed ? 'opacity-50 cursor-not-allowed bg-slate-300 text-slate-500' : 'bg-red-600 hover:bg-red-700 text-white shadow-sm shadow-red-600/20'" class="px-5 py-2.5 text-sm font-bold rounded-xl transition-all">
                        Ya, Lanjutkan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
