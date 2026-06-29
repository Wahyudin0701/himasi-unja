@extends('layouts.dashboard')

@section('title', 'Kelola Bidang')

@section('breadcrumbs')
    <span class="text-slate-700">Kepengurusan</span>
    <i class="ph-bold ph-caret-right text-slate-400 text-xs"></i>
    <a href="{{ route('super_admin.members.index') }}" class="text-slate-500 hover:text-brand-600 transition-colors">Pengurus</a>
@endsection

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Kelola Bidang
            </h1>
            <p class="text-sm text-slate-500 mt-1.5 font-medium">Divisi {{ $division->name }}</p>
        </div>
        <a href="{{ route('super_admin.members.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali
        </a>
    </div>
</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Bidang -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm sticky top-24">
            <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle text-brand-500"></i>
                Tambah Bidang Baru
            </h2>
            <form action="{{ route('super_admin.sub_divisions.store', $division->id) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Bidang <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="Contoh: Bidang Internal"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 focus:bg-white transition-all outline-none @error('name') border-red-300 bg-red-50 @enderror">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="w-full px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-brand-500/20">
                        Simpan Bidang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Bidang -->
    <div class="lg:col-span-2 space-y-4">
        @forelse($division->subDivisions as $subDivision)
            <div x-data="{ isOpen: false, isEditing: false }" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition-colors hover:bg-slate-50 cursor-pointer" @click="if(!isEditing) isOpen = !isOpen">
                    <div class="flex items-start w-full">
                        <div class="flex-1">
                            {{-- View Mode --}}
                            <div x-show="!isEditing">
                                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                                    {{ $subDivision->name }}
                                </h3>
                                <p class="text-xs font-medium text-slate-500 mt-1 flex items-center gap-1.5">
                                    <i class="ph-fill ph-users"></i> {{ $subDivision->members->count() }} Anggota Terdaftar
                                    <span class="mx-1 text-slate-300">•</span>
                                    <span class="text-brand-600 font-bold" x-text="isOpen ? 'Tutup Detail' : 'Lihat Anggota'"></span>
                                </p>
                            </div>

                            {{-- Edit Mode --}}
                            <div x-show="isEditing" style="display: none;" @click.stop class="w-full">
                                <form action="{{ route('super_admin.sub_divisions.update', $subDivision->id) }}" method="POST" class="flex items-center gap-2 w-full mt-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $subDivision->name }}" required class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-900 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none w-full max-w-sm">
                                    <button type="submit" class="px-3 py-1.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-bold rounded-lg transition-all shadow-sm">Simpan</button>
                                    <button type="button" @click="isEditing = false" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold rounded-lg transition-all">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div x-show="!isEditing" class="shrink-0 flex items-center gap-2" @click.stop>
                        <button type="button" @click="isEditing = true" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-bold rounded-lg border border-slate-200 transition-colors">
                            <i class="ph-bold ph-pencil-simple"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </button>

                        <form id="delete-form-{{ $subDivision->id }}" action="{{ route('super_admin.sub_divisions.destroy', $subDivision->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            @if($subDivision->members->count() > 0)
                                <button type="button" onclick="Swal.fire({icon: 'error', title: 'Oops...', text: 'Tidak dapat menghapus bidang ini karena masih memiliki {{ $subDivision->members->count() }} anggota yang terdaftar.', confirmButtonColor: '#3b82f6'})" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-lg border border-slate-200 cursor-not-allowed">
                                    <i class="ph-bold ph-trash"></i>
                                    <span class="hidden sm:inline">Hapus</span>
                                </button>
                            @else
                                <button type="button" onclick="confirmDelete({{ $subDivision->id }})" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg border border-red-100 transition-colors">
                                    <i class="ph-bold ph-trash"></i>
                                    <span class="hidden sm:inline">Hapus</span>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Accordion Content: Anggota Bidang --}}
                <div x-show="isOpen" style="display: none;" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="border-t border-slate-100 bg-slate-50/50">
                    <div class="p-5 sm:p-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Pengurus Bidang {{ $subDivision->name }}</h4>
                        @if($subDivision->members->count() > 0)
                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-3">
                                @foreach($subDivision->members as $member)
                                    <div class="bg-white rounded-xl border border-slate-200 p-3 flex items-center gap-3 shadow-sm min-w-0">
                                        @if($member->user->avatar)
                                            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-slate-200">
                                                <img src="{{ file_exists(public_path('storage/' . $member->user->avatar)) ? asset('storage/' . $member->user->avatar) : asset($member->user->avatar) }}" alt="{{ $member->user->name }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center font-black text-sm shrink-0">
                                                {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <h5 class="font-bold text-sm text-slate-900 truncate">{{ $member->user->name }}</h5>
                                            <p class="text-xs text-brand-600 font-medium truncate mt-0.5">{{ $member->position_title ?: ($member->orgPosition->name ?? 'Anggota Bidang') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-white rounded-xl border border-slate-200 border-dashed">
                                <p class="text-slate-500 text-sm font-medium">Belum ada anggota yang ditugaskan ke bidang ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-slate-50 border border-slate-200 border-dashed rounded-3xl p-8 sm:p-12 text-center">
                <div class="w-16 h-16 bg-white border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="ph-fill ph-list-dashes text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-slate-900 font-bold mb-1">Belum ada Bidang</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                    Divisi ini belum memiliki pembagian bidang. Silakan tambahkan bidang baru melalui formulir di samping.
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Bidang?',
            text: "Data bidang yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
