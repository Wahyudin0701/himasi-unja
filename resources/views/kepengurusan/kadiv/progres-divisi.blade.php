@extends('layouts.dashboard')

@section('title', 'Progres Divisi')

@section('content')
<div class="max-w-7xl mx-auto pb-10 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Progres Divisi</h1>
            <p class="text-slate-500 mt-1 text-sm">Pantau progres setiap anggota beserta capaian proker Non-Event yang mereka kerjakan.</p>
        </div>
    </div>

    @php
        $totalProkers = 0;
        $totalProgressAcc = 0;
        foreach ($members as $member) {
            $totalProkers += $member->prokers->count();
            $totalProgressAcc += $member->prokers->sum('progress_percentage');
        }
        $divisionProgress = $totalProkers > 0 ? round($totalProgressAcc / $totalProkers) : 0;
    @endphp

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden mb-8">

            {{-- Division Header --}}
            <div class="border-b border-slate-100">
                <div class="px-6 md:px-8 pt-6 pb-4 flex flex-col sm:flex-row sm:items-center gap-5">
                    <div class="flex items-center gap-4 flex-1">
                    <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                        <i class="ph-fill ph-users-three text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-900">{{ $division->name }}</h2>
                        <p class="text-xs font-semibold text-brand-600 mt-0.5">Kepengurusan</p>
                    </div>
                </div>
                <div class="flex items-center gap-6 sm:gap-8">
                    <div class="text-center">
                        <p class="text-xl font-black text-slate-900">{{ $members->count() }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Anggota</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xl font-black text-slate-900">{{ $totalProkers }}</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Total Proker PJ</p>
                    </div>
                    <div class="text-center min-w-[80px]">
                        <p class="text-xl font-black {{ $divisionProgress == 100 && $totalProkers > 0 ? 'text-emerald-600' : 'text-brand-600' }}">{{ $divisionProgress }}%</p>
                        <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mt-0.5">Rata-rata Progres</p>
                    </div>
                </div>
                </div> {{-- End of Header Flex Container --}}

                {{-- Division Progress Bar --}}
                <div class="px-6 md:px-8 pb-6">
                    <div class="h-1.5 bg-slate-100 w-full rounded-full overflow-hidden">
                        <div class="h-full {{ $divisionProgress == 100 && $totalProkers > 0 ? 'bg-emerald-500' : 'bg-brand-500' }} transition-all duration-700 rounded-full"
                             style="width: {{ $divisionProgress }}%"></div>
                    </div>
                </div>
            </div> {{-- End of Border Bottom Container --}}

            {{-- Members List --}}
            <div class="p-6 md:p-8">
                @if($members->isEmpty())
                    <div class="py-12 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                            <i class="ph ph-user-minus text-2xl text-slate-300"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Belum Ada Anggota</h3>
                        <p class="text-xs text-slate-500 mt-1">Belum ada anggota yang bergabung di divisi ini.</p>
                    </div>
                @else
                    <div class="space-y-4" x-data="{ openMember: null }">
                        @foreach($members as $member)
                            @php
                                $user = $member->user;
                                $prokers = $member->prokers;

                                $totalProkersMember = $prokers->count();
                                $totalProgressMember = $prokers->sum('progress_percentage');
                                $memberProgress = $totalProkersMember > 0 ? round($totalProgressMember / $totalProkersMember) : 0;
                            @endphp

                            <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white transition-all duration-200"
                                 :class="{ 'border-brand-300 shadow-md ring-4 ring-brand-50': openMember === {{ $member->id }} }">

                                {{-- Member Row / Trigger --}}
                                <div @click="openMember = openMember === {{ $member->id }} ? null : {{ $member->id }}"
                                     class="flex flex-col md:flex-row md:items-center gap-4 p-5 cursor-pointer hover:bg-slate-50 transition-colors">

                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="relative">
                                            @if($user->avatar)
                                                <img src="{{ file_exists(public_path('storage/' . $user->avatar)) ? asset('storage/' . $user->avatar) : asset($user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-100 to-brand-50 flex items-center justify-center border-2 border-white shadow-sm text-brand-600 font-bold text-sm">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 group-hover:text-brand-600 transition-colors">{{ $user->name }}</h3>
                                            <p class="text-xs font-semibold text-slate-500 mt-0.5">{{ $member->orgPosition->name ?? 'Anggota' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6 justify-between md:justify-end border-t md:border-t-0 border-slate-100 pt-3 md:pt-0">
                                        <div class="flex gap-4 sm:gap-6 w-full md:w-auto">
                                            <div class="text-center md:text-right">
                                                <p class="text-sm font-black text-slate-800">{{ $totalProkersMember }}</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Proker PJ</p>
                                            </div>
                                            <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                                            <div class="text-center md:text-right min-w-[60px]">
                                                <p class="text-sm font-black {{ $memberProgress == 100 && $totalProkersMember > 0 ? 'text-emerald-600' : 'text-brand-600' }}">{{ $memberProgress }}%</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata</p>
                                            </div>
                                        </div>

                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 transition-transform duration-300"
                                             :class="{ 'rotate-180 bg-brand-100 text-brand-600': openMember === {{ $member->id }} }">
                                            <i class="ph-bold ph-caret-down"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Expanded Details --}}
                                <div x-show="openMember === {{ $member->id }}" x-collapse>
                                    <div class="p-5 md:p-6 bg-slate-50 border-t border-slate-100">
                                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                            <i class="ph-bold ph-list-dashes"></i> Daftar Program Kerja Penanggung Jawab
                                        </h4>

                                        @if($prokers->isEmpty())
                                            <div class="py-6 text-center bg-white rounded-xl border border-dashed border-slate-200">
                                                <p class="text-xs font-medium text-slate-500">Anggota ini belum diberikan tanggung jawab untuk proker apapun.</p>
                                            </div>
                                        @else
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($prokers as $proker)
                                                    <a href="{{ route('kepengurusan.kadiv.proker.show', $proker->id) }}" class="bg-white border border-slate-200 rounded-xl p-4 hover:border-brand-300 hover:shadow-sm transition-all group block">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider
                                                                @if($proker->status == 'planning') bg-slate-100 text-slate-600
                                                                @elseif($proker->status == 'ongoing') bg-blue-100 text-blue-600
                                                                @elseif($proker->status == 'completed') bg-emerald-100 text-emerald-600
                                                                @elseif($proker->status == 'cancelled') bg-rose-100 text-rose-600
                                                                @endif
                                                            ">
                                                                {{ $proker->status }}
                                                            </span>
                                                            <span class="text-xs font-black {{ $proker->progress_percentage == 100 ? 'text-emerald-500' : 'text-brand-600' }}">
                                                                {{ $proker->progress_percentage }}%
                                                            </span>
                                                        </div>
                                                        <h5 class="text-sm font-bold text-slate-900 group-hover:text-brand-600 transition-colors mb-2">{{ $proker->name }}</h5>
                                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                            <div class="h-full {{ $proker->progress_percentage == 100 ? 'bg-emerald-500' : 'bg-brand-500' }} rounded-full" style="width: {{ $proker->progress_percentage }}%"></div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
    </div>
</div>
@endsection
