@extends('layouts.dashboard')

@section('title', 'RAB Semua Divisi')

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="text-slate-700">Ketua Pelaksana</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="font-bold text-brand-600">RAB Semua Divisi</span>
@endsection

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ filterDivision: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">RAB Semua Divisi</h1>
            <p class="text-sm font-medium text-slate-500">
                Pantau seluruh Rencana Anggaran Biaya yang diajukan oleh divisi untuk {{ $event->name }}.
            </p>
        </div>
        <div>
            <a href="{{ route('kepanitiaan.ketupel.rab.print', $event) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                <i class="ph-bold ph-download-simple text-lg"></i>
                Download RAB
            </a>
        </div>
    </div>

    <!-- Filter & Stats -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Grand Total Card -->
        <div class="w-full lg:w-1/3 bg-brand-600 rounded-2xl p-6 shadow-sm shadow-brand-500/20 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-brand-200 text-sm font-bold uppercase tracking-widest mb-1">Grand Total Anggaran</p>
                <h2 class="text-3xl font-black mb-4">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h2>
                <div class="bg-brand-500/30 rounded-lg p-3 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-info text-xl text-brand-200"></i>
                        <p class="text-xs text-brand-100 font-medium">Total RAB dari seluruh divisi yang terdaftar pada sistem.</p>
                    </div>
                </div>
            </div>
            <i class="ph-fill ph-money absolute -bottom-6 -right-6 text-[120px] text-brand-500/30 transform -rotate-12"></i>
        </div>

        <!-- Filter Box -->
        <div class="w-full lg:w-2/3 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-4">
                <i class="ph-fill ph-funnel text-slate-400"></i>
                <span class="text-sm font-bold text-slate-600 uppercase tracking-widest">Filter Data</span>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-widest">Pilih Divisi</label>
                    <select x-model="filterDivision" class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5">
                        <option value="">Semua Divisi (Tampilkan Semua)</option>
                        @foreach($event->divisions as $division)
                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Data RAB -->
    <div class="space-y-6">
        @forelse($rabs as $divisionId => $items)
            @php 
                $div = $items->first()->division; 
                $subTotal = $items->sum('total_price');
            @endphp
            <div x-show="filterDivision === '' || filterDivision == '{{ $divisionId }}'" class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center font-black">
                            {{ substr($div->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800">{{ $div->name }}</h3>
                            <p class="text-xs font-semibold text-slate-500">{{ $items->count() }} Item RAB</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-0.5">Subtotal Divisi</p>
                        <p class="text-lg font-black text-emerald-600">Rp {{ number_format($subTotal, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs font-bold text-slate-400 uppercase bg-white border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 w-16">No</th>
                                <th class="px-6 py-3">Nama Barang</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Satuan</th>
                                <th class="px-6 py-3">Harga Satuan</th>
                                <th class="px-6 py-3">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($items as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3 font-semibold text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3 font-bold text-slate-700">{{ $item->item_name }}</td>
                                    <td class="px-6 py-3 font-semibold text-slate-600">{{ $item->quantity }}</td>
                                    <td class="px-6 py-3 text-slate-500 font-medium">{{ $item->unit }}</td>
                                    <td class="px-6 py-3 font-semibold text-slate-500">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 font-bold text-slate-800">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-12 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="ph-fill ph-file-dashed text-4xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-1">Belum Ada RAB</h3>
                <p class="text-sm font-medium text-slate-500">Belum ada divisi yang mengajukan Rencana Anggaran Biaya untuk acara ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
