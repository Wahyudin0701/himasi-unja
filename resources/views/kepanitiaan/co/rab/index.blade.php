@extends('layouts.dashboard')

@section('title', 'RAB ' . $division->name)

@section('breadcrumbs')
    <span class="text-slate-700">Kepanitiaan</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="text-slate-700">CO Divisi</span>
    <i class="ph-bold ph-caret-right text-xs text-slate-400"></i>
    <span class="font-bold text-brand-600">RAB</span>
@endsection

@section('content')
<div class="space-y-6 max-w-7xl mx-auto" x-data="{ showCreateModal: false, showEditModal: false, showDeleteModal: false, editForm: {}, deleteUrl: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 mb-1">RAB Divisi {{ $division->name }}</h1>
            <p class="text-sm font-medium text-slate-500">
                Kelola Rencana Anggaran Biaya untuk {{ $event->name }}.
            </p>
        </div>
        <div>
            <button @click="showCreateModal = true" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="ph-bold ph-plus"></i> Tambah Item
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                <i class="ph-fill ph-list-numbers text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total Item</p>
                <p class="text-2xl font-black text-slate-800">{{ $rabs->count() }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Total Anggaran</p>
                <p class="text-2xl font-black text-slate-800">Rp {{ number_format($rabs->sum('total_price'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-sm font-semibold border border-emerald-100 flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs font-bold text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Satuan</th>
                        <th class="px-6 py-4">Harga Satuan</th>
                        <th class="px-6 py-4">Total Harga</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rabs as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 font-semibold text-slate-600">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $item->item_name }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-600">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $item->unit }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 font-bold text-brand-600">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editForm = {{ json_encode($item) }}; showEditModal = true" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors" title="Edit Item">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <button @click="deleteUrl = '{{ route('kepanitiaan.co.rab.destroy', [$event->id, $division->id, $item->id]) }}'; showDeleteModal = true" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors" title="Hapus Item">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i class="ph-fill ph-file-dashed text-4xl mb-3 text-slate-300"></i>
                                    <p class="font-medium">Belum ada item RAB yang ditambahkan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($rabs->count() > 0)
                <tfoot class="bg-slate-50 border-t border-slate-200">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right font-black text-slate-700 uppercase">Grand Total</td>
                        <td colspan="2" class="px-6 py-4 font-black text-emerald-600 text-lg">Rp {{ number_format($rabs->sum('total_price'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showCreateModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-black text-slate-800">Tambah Item RAB</h3>
                    <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                
                <form action="{{ route('kepanitiaan.co.rab.store', [$event->id, $division->id]) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Nama Barang</label>
                            <input type="text" name="item_name" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5" placeholder="Contoh: Banner Utama">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Jumlah</label>
                                <input type="number" min="1" name="quantity" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5" placeholder="1">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Satuan</label>
                                <input type="text" name="unit" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5" placeholder="Contoh: pcs, buah">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Harga Satuan (Rp)</label>
                            <input type="number" min="0" name="unit_price" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5" placeholder="Contoh: 150000">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm transition-colors">
                            Simpan Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl p-6 overflow-hidden">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-black text-slate-800">Edit Item RAB</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                
                <form :action="`{{ route('kepanitiaan.co.rab.index', [$event->id, $division->id]) }}/${editForm.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Nama Barang</label>
                            <input type="text" name="item_name" x-model="editForm.item_name" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Jumlah</label>
                                <input type="number" min="1" name="quantity" x-model="editForm.quantity" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Satuan</label>
                                <input type="text" name="unit" x-model="editForm.unit" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-1.5">Harga Satuan (Rp)</label>
                            <input type="number" min="0" name="unit_price" x-model="editForm.unit_price" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 bg-slate-50 px-4 py-2.5">
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 rounded-xl shadow-sm transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl p-6 overflow-hidden">
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4 text-rose-500">
                    <i class="ph-fill ph-warning-circle text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-2">Hapus Item RAB?</h3>
                <p class="text-sm font-medium text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Item ini akan dihapus secara permanen dari RAB divisi Anda.</p>
                
                <div class="flex justify-center gap-3">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-rose-500 hover:bg-rose-600 rounded-xl shadow-sm transition-colors">
                            Ya, Hapus Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
