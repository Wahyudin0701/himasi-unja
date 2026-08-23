@extends('layouts.dashboard')

@section('title', 'Kas Himasi')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kas Himasi</h1>
        <p class="text-sm text-slate-500 mt-1">Pencatatan uang masuk dan keluar pada periode kepengurusan aktif.</p>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="openModal('modal-add-pemasukan')" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-trend-up"></i>
            Tambah Pemasukan
        </button>
        <button onclick="openModal('modal-add-pengeluaran')" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm">
            <i class="ph-bold ph-trend-down"></i>
            Tambah Pengeluaran
        </button>
    </div>
</div>

{{-- Filter & Statistik Bulan Ini --}}
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-8">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <form action="{{ route('kepengurusan.bendahara.finances.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
            <select name="month" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-slate-700" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @for($m=1; $m<=12; $m++)
                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                @endfor
            </select>
        </form>

        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Pemasukan {{ $month ? 'Bulan Ini' : 'Periode Ini' }}</p>
                <p class="text-sm font-bold text-slate-900">Rp {{ number_format($monthPemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="w-px h-8 bg-slate-200"></div>
            <div class="text-right">
                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-0.5">Pengeluaran {{ $month ? 'Bulan Ini' : 'Periode Ini' }}</p>
                <p class="text-sm font-bold text-slate-900">Rp {{ number_format($monthPengeluaran, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest">Riwayat Transaksi</h3>
        <div class="px-4 py-2 bg-slate-100 rounded-lg text-sm font-bold text-slate-700">
            Saldo Keseluruhan: <span class="{{ $saldo >= 0 ? 'text-emerald-600' : 'text-red-600' }}">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Keterangan</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Pemasukan</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Pengeluaran</th>
                    <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-right whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-slate-900">{{ $trx->date->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-900">{{ $trx->description }}</p>
                            <span class="inline-block px-2 py-0.5 mt-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600">{{ $trx->category }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($trx->type === 'pemasukan')
                                <span class="text-sm font-bold text-emerald-600">+ Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($trx->type === 'pengeluaran')
                                <span class="text-sm font-bold text-red-600">- Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($trx->proof_file)
                                <a href="{{ Storage::url($trx->proof_file) }}" target="_blank" class="inline-flex items-center justify-center w-7 h-7 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors tooltip" data-tip="Lihat Bukti">
                                    <i class="ph-bold ph-receipt"></i>
                                </a>
                                @endif
                                <button onclick="openEditModal({{ $trx->id }}, '{{ $trx->type }}', '{{ $trx->date->format('Y-m-d') }}', '{{ $trx->category }}', {{ $trx->amount }}, '{{ $trx->description }}')" class="inline-flex items-center justify-center w-7 h-7 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg transition-colors tooltip" data-tip="Edit">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </button>
                                <form action="{{ route('kepengurusan.bendahara.finances.destroy', $trx) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Saldo akan disesuaikan secara otomatis.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-7 h-7 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors tooltip" data-tip="Hapus">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="ph-bold ph-wallet text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">Belum ada transaksi</h3>
                            <p class="text-xs text-slate-500">Mulai catat pemasukan atau pengeluaran kas Himpunan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($transactions->hasPages())
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
@endif

{{-- Modal Tambah Pemasukan --}}
<div id="modal-add-pemasukan" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-add-pemasukan')"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl relative" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
                    <h3 class="text-lg font-black text-slate-900">Catat Pemasukan</h3>
                    <button onclick="closeModal('modal-add-pemasukan')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('kepengurusan.bendahara.finances.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="pemasukan">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                                <input type="number" name="amount" min="0" placeholder="Misal: 50000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kategori</label>
                                <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                    <option value="Uang Kas Anggota">Uang Kas Anggota</option>
                                    <option value="Dana Usaha">Dana Usaha</option>
                                    <option value="Subsidi Fakultas">Subsidi Fakultas</option>
                                    <option value="Donasi / Sponsorship">Donasi / Sponsorship</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Keterangan</label>
                                <textarea name="description" rows="3" placeholder="Detail transaksi..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Bukti Struk (Opsional)</label>
                                <input type="file" name="proof_file" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                <p class="text-xs text-slate-500 mt-1">Hanya menerima gambar (JPG, PNG)</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                                Simpan Pemasukan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Pengeluaran --}}
<div id="modal-add-pengeluaran" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-add-pengeluaran')"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl relative" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
                    <h3 class="text-lg font-black text-slate-900">Catat Pengeluaran</h3>
                    <button onclick="closeModal('modal-add-pengeluaran')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form action="{{ route('kepengurusan.bendahara.finances.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="pengeluaran">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                                <input type="number" name="amount" min="0" placeholder="Misal: 150000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kategori</label>
                                <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                    <option value="Operasional Kesekretariatan">Operasional Kesekretariatan</option>
                                    <option value="Kegiatan / Proker">Kegiatan / Proker</option>
                                    <option value="Konsumsi Rapat">Konsumsi Rapat</option>
                                    <option value="Pembelian Inventaris">Pembelian Inventaris</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Keterangan</label>
                                <textarea name="description" rows="3" placeholder="Detail pengeluaran (misal: Beli ATK untuk sekretariat)..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Bukti Struk (Opsional)</label>
                                <input type="file" name="proof_file" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                <p class="text-xs text-slate-500 mt-1">Hanya menerima gambar (JPG, PNG)</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Transaksi --}}
<div id="modal-edit-trx" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-edit-trx')"></div>
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl relative" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
                    <h3 class="text-lg font-black text-slate-900">Edit Transaksi</h3>
                    <button onclick="closeModal('modal-edit-trx')" class="w-8 h-8 flex items-center justify-center rounded-xl hover:bg-slate-100 text-slate-500 transition-colors">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                <div class="p-6">
                    <form id="form-edit-trx" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis Transaksi</label>
                                <select name="type" id="edit-type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                                    <option value="pemasukan">Pemasukan</option>
                                    <option value="pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Tanggal</label>
                                <input type="date" name="date" id="edit-date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                                <input type="number" name="amount" id="edit-amount" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kategori (Manual/Pilih)</label>
                                <input type="text" name="category" id="edit-category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Keterangan</label>
                                <textarea name="description" id="edit-description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500" required></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Ganti Bukti Struk (Opsional)</label>
                                <input type="file" name="proof_file" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, type, date, category, amount, description) {
        const form = document.getElementById('form-edit-trx');
        form.action = `/kepengurusan/bendahara/finances/${id}`; // Menggunakan rute dinamis sesuai ID
        
        document.getElementById('edit-type').value = type;
        document.getElementById('edit-date').value = date;
        document.getElementById('edit-amount').value = amount;
        document.getElementById('edit-category').value = category;
        document.getElementById('edit-description').value = description;
        
        openModal('modal-edit-trx');
    }
</script>
@endsection
