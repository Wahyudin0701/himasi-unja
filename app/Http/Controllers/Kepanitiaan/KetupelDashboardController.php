<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventCommittee;

class KetupelDashboardController extends Controller
{
    /**
     * Slug role yang termasuk "Ketupel" (Ketua + Wakil Ketua Pelaksana).
     * Bukan Sekpel/Benpel — mereka punya dashboard sendiri di Sprint 2.
     */
    const KETUPEL_SLUGS = ['ketua-pelaksana', 'wakil-ketua-pelaksana'];

    /**
     * Dashboard utama Ketua Pelaksana.
     * Hanya menampilkan event di mana user berperan sebagai Ketupel/Wakil — 
     * bukan Sekpel, Benpel, CO, atau Anggota.
     * 
     * Rule: 1 event = 1 Ketupel (dipastikan di level query dan validasi assign).
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil hanya event di mana user adalah Ketupel atau Wakil Ketua Pelaksana
        $myCommittees = $user->eventCommittees()
            ->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))
            ->whereHas('event', fn($q) => $q->whereNotIn('status', ['cancelled']))
            ->with(['event.divisions', 'event.committees', 'role'])
            ->get();

        // Ambil event unik (seharusnya tidak ada duplikat karena 1 user 1 event via unique constraint)
        $myEvents = $myCommittees->pluck('event')->unique('id')->filter();

        // Statistik
        $totalEvents   = $myEvents->count();
        $activeEvents  = $myEvents->whereIn('status', ['planning', 'preparation', 'ongoing'])->count();
        $totalDivisions = 0;
        $totalMembers  = 0;

        foreach ($myEvents as $event) {
            $totalDivisions += $event->divisions->count();
            $totalMembers   += $event->committees->count();
        }

        return view('kepanitiaan.ketupel.dashboard', compact(
            'user', 'myEvents', 'myCommittees',
            'totalEvents', 'activeEvents', 'totalDivisions', 'totalMembers'
        ));
    }

    /**
     * Manajemen Panitia — otomatis mengarah ke halaman kelola tim
     * dari event aktif yang dipegang Ketupel.
     */
    public function manajemenPanitia()
    {
        $user = auth()->user();

        // Cari event aktif di mana user adalah Ketupel
        $assignment = $user->eventCommittees()
            ->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->with('event')
            ->first();

        if (!$assignment) {
            return redirect()->route('kepanitiaan.ketupel.dashboard')
                ->with('error', 'Anda tidak memiliki acara aktif saat ini.');
        }

        return redirect()->route('kepanitiaan.ketupel.manage-team', $assignment->event);
    }

    /**
     * Halaman manajemen tim kepanitiaan untuk satu event.
     * Hanya bisa diakses oleh Ketupel/Wakil di event tersebut.
     */
    public function manageTeam(Event $event)
    {
        $user = auth()->user();

        // Pastikan user adalah Ketupel (atau Wakil) di event ini secara spesifik
        $isKetupel = $user->eventCommittees()
            ->where('event_id', $event->id)
            ->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))
            ->exists();

        if (!$isKetupel) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        $event->load([
            'divisions',
            'committees.user',
            'committees.role',
            'committees.division',
        ]);

        $allUsers = \App\Models\User::with(['memberships.division'])
            ->whereNotIn('global_role', ['super_admin', 'pembina', 'dp', 'kahim', 'wakahim', 'sekretaris', 'bendahara'])
            ->whereDoesntHave('eventCommittees', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->orderBy('name')
            ->get();
        
        $roles = \App\Models\Kepanitiaan\CommitteeRole::all();

        return view('kepanitiaan.ketupel.manage_team', compact('user', 'event', 'allUsers', 'roles'));
    }

    /**
     * Validasi: Pastikan event belum memiliki Ketupel.
     * Dipanggil sebelum assign role Ketupel ke user baru.
     * Rule: 1 event = 1 Ketupel (ditegakkan di application layer).
     */
    public static function eventAlreadyHasKetupel(int $eventId): bool
    {
        return EventCommittee::where('event_id', $eventId)
            ->whereHas('role', fn($q) => $q->where('slug', 'ketua-pelaksana'))
            ->exists();
    }

    /**
     * Menambahkan anggota tim (Sekpel/Benpel/CO/Anggota).
     */
    public function storeTeamMember(Request $request, Event $event)
    {
        // Pastikan user adalah Ketupel di event ini
        $user = auth()->user();
        if (!$user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))->exists()) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        $validated = $request->validate([
            'role_id' => 'required|exists:committee_roles,id',
            'division_id' => 'nullable|exists:event_divisions,id', // Wajib untuk CO/Anggota, Null untuk Sekpel/Benpel
            
            // Mode 1: User existing
            'user_id' => 'nullable|exists:users,id',
            
            // Mode 2: User baru
            'name' => 'required_without:user_id|nullable|string|max:255',
            'email' => 'required_without:user_id|nullable|email|max:255',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $targetUser = null;

            if (!empty($validated['user_id'])) {
                $targetUser = \App\Models\User::findOrFail($validated['user_id']);
            } else {
                // Buat user baru atau ambil kalau sudah ada berdasarkan email
                $targetUser = \App\Models\User::firstOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['name'],
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'global_role' => 'anggota', // Role default HIMASI
                    ]
                );
            }

            // Validasi: User tidak boleh punya role ganda di 1 event
            $alreadyAssigned = EventCommittee::where('event_id', $event->id)
                ->where('user_id', $targetUser->id)
                ->exists();

            if ($alreadyAssigned) {
                return back()->with('error', "{$targetUser->name} sudah terdaftar di kepanitiaan ini.");
            }

            // Validasi: Jika tambah CO, pastikan divisi tersebut belum ada CO-nya
            $role = \App\Models\Kepanitiaan\CommitteeRole::find($validated['role_id']);
            if ($role->slug === 'co-divisi') {
                $hasCO = EventCommittee::where('event_id', $event->id)
                    ->where('event_division_id', $validated['division_id'])
                    ->where('committee_role_id', $role->id)
                    ->exists();
                
                if ($hasCO) {
                    return back()->with('error', "Divisi ini sudah memiliki Koordinator (CO).");
                }
            }

            // Assign role
            EventCommittee::create([
                'event_id' => $event->id,
                'user_id' => $targetUser->id,
                'committee_role_id' => $validated['role_id'],
                'event_division_id' => $validated['division_id'] ?? null,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return back()->with('success', "{$targetUser->name} berhasil ditambahkan sebagai {$role->name}.");
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menambahkan anggota tim: ' . $e->getMessage());
        }
    }

    /**
     * Mengganti anggota tim yang sudah ada.
     */
    public function updateTeamMember(Request $request, Event $event, EventCommittee $committee)
    {
        // Pastikan user adalah Ketupel di event ini
        $user = auth()->user();
        if (!$user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))->exists()) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        $validated = $request->validate([
            // Mode 1: User existing
            'user_id' => 'nullable|exists:users,id',
            // Mode 2: User baru
            'name' => 'required_without:user_id|nullable|string|max:255',
            'email' => 'required_without:user_id|nullable|email|max:255',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $targetUser = null;

            if (!empty($validated['user_id'])) {
                $targetUser = \App\Models\User::findOrFail($validated['user_id']);
            } else {
                $targetUser = \App\Models\User::firstOrCreate(
                    ['email' => $validated['email']],
                    [
                        'name' => $validated['name'],
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'global_role' => 'anggota',
                    ]
                );
            }

            // Validasi: User tidak boleh punya role ganda di 1 event
            $alreadyAssigned = EventCommittee::where('event_id', $event->id)
                ->where('user_id', $targetUser->id)
                ->where('id', '!=', $committee->id)
                ->exists();

            if ($alreadyAssigned) {
                return back()->with('error', "{$targetUser->name} sudah terdaftar di kepanitiaan ini.");
            }

            $committee->update([
                'user_id' => $targetUser->id,
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return back()->with('success', "Anggota berhasil diganti menjadi {$targetUser->name}.");
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal mengganti anggota tim: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus anggota tim.
     */
    public function removeTeamMember(Event $event, EventCommittee $committee)
    {
        // Pastikan user adalah Ketupel di event ini
        $user = auth()->user();
        if (!$user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))->exists()) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        // Jangan izinkan Ketupel menghapus dirinya sendiri dari sini
        if (in_array($committee->role->slug, self::KETUPEL_SLUGS)) {
            return back()->with('error', 'Ketua Pelaksana tidak dapat dihapus melalui form ini.');
        }

        $committeeName = $committee->user->name;
        $committee->delete();

        return back()->with('success', "{$committeeName} berhasil dihapus dari kepanitiaan.");
    }

    /**
     * Menampilkan halaman Tambah Divisi.
     */
    public function createDivision(Event $event)
    {
        // Pastikan user adalah Ketupel di event ini
        $user = auth()->user();
        if (!$user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))->exists()) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        $allUsers = \App\Models\User::with(['memberships.division'])
            ->whereNotIn('global_role', ['super_admin', 'pembina', 'dp', 'kahim', 'wakahim', 'sekretaris', 'bendahara'])
            ->whereDoesntHave('eventCommittees', function ($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->orderBy('name')
            ->get();

        return view('kepanitiaan.ketupel.create_division', compact('event', 'allUsers'));
    }

    /**
     * Menambahkan Divisi Kepanitiaan.
     */
    public function storeDivision(Request $request, Event $event)
    {
        // Pastikan user adalah Ketupel di event ini
        $user = auth()->user();
        if (!$user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))->exists()) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            
            // Validasi CO
            'co_type' => 'required|in:existing,new',
            'co_user_id' => 'required_if:co_type,existing|nullable|exists:users,id',
            'co_name' => 'required_if:co_type,new|nullable|string|max:255',
            'co_email' => 'required_if:co_type,new|nullable|email|max:255',

            // Validasi Anggota (Array)
            'anggota' => 'required|array|min:1',
            'anggota.*.type' => 'required|in:existing,new',
            'anggota.*.user_id' => 'required_if:anggota.*.type,existing|nullable|exists:users,id',
            'anggota.*.name' => 'required_if:anggota.*.type,new|nullable|string|max:255',
            'anggota.*.email' => 'required_if:anggota.*.type,new|nullable|email|max:255',
        ]);

        $userIds = [];
        if ($validated['co_type'] === 'existing' && !empty($validated['co_user_id'])) {
            $userIds[] = $validated['co_user_id'];
        }
        foreach ($validated['anggota'] as $anggota) {
            if ($anggota['type'] === 'existing' && !empty($anggota['user_id'])) {
                $userIds[] = $anggota['user_id'];
            }
        }

        if (count($userIds) !== count(array_unique($userIds))) {
            return back()->withInput()->with('error', 'Tidak boleh ada anggota terdaftar yang dipilih lebih dari satu kali dalam form yang sama.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 1. Buat Divisi
            $division = \App\Models\Kepanitiaan\EventDivision::create([
                'event_id' => $event->id,
                'name' => $validated['name'],
                'slug' => \Illuminate\Support\Str::slug($validated['name']),
                'sort_order' => \App\Models\Kepanitiaan\EventDivision::where('event_id', $event->id)->max('sort_order') + 1,
            ]);

            $roles = \App\Models\Kepanitiaan\CommitteeRole::all();
            $coRole = $roles->where('slug', 'co-divisi')->first();
            $anggotaRole = $roles->where('slug', 'anggota')->first();

            // 2. Buat / Assign CO
            if ($validated['co_type'] === 'existing') {
                $coUser = \App\Models\User::findOrFail($validated['co_user_id']);
            } else {
                $coUser = \App\Models\User::firstOrCreate(
                    ['email' => $validated['co_email']],
                    [
                        'name' => $validated['co_name'],
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'global_role' => 'anggota',
                    ]
                );
            }

            EventCommittee::create([
                'event_id' => $event->id,
                'user_id' => $coUser->id,
                'committee_role_id' => $coRole->id,
                'event_division_id' => $division->id,
            ]);

            // 3. Buat / Assign Anggota
            foreach ($validated['anggota'] as $anggotaData) {
                // Konversi string kosong jadi null
                $aType = $anggotaData['type'] ?? 'existing';
                $aUserId = $anggotaData['user_id'] ?? null;
                if ($aUserId === '') $aUserId = null;
                $aEmail = $anggotaData['email'] ?? null;
                $aName = $anggotaData['name'] ?? null;

                // Lewati jika kosong (misal salah kirim)
                if ($aType === 'existing' && !$aUserId) continue;
                if ($aType === 'new' && !$aEmail) continue;

                if ($aType === 'existing') {
                    $angUser = \App\Models\User::findOrFail($aUserId);
                } else {
                    $angUser = \App\Models\User::firstOrCreate(
                        ['email' => $aEmail],
                        [
                            'name' => $aName,
                            'password' => \Illuminate\Support\Facades\Hash::make('password'),
                            'global_role' => 'anggota',
                        ]
                    );
                }

                EventCommittee::create([
                    'event_id' => $event->id,
                    'user_id' => $angUser->id,
                    'committee_role_id' => $anggotaRole->id,
                    'event_division_id' => $division->id,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('kepanitiaan.ketupel.manage-team', $event)->with('success', "Divisi {$validated['name']} beserta anggotanya berhasil ditambahkan.");

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menambahkan divisi: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus Divisi Kepanitiaan.
     */
    public function destroyDivision(Event $event, \App\Models\Kepanitiaan\EventDivision $division)
    {
        // Pastikan user adalah Ketupel di event ini
        $user = auth()->user();
        if (!$user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', self::KETUPEL_SLUGS))->exists()) {
            abort(403, 'Anda bukan Ketua Pelaksana di acara ini.');
        }

        if ($division->event_id !== $event->id) {
            abort(404);
        }

        if ($division->name === 'Tim Inti') {
            return back()->with('error', 'Tim Inti tidak dapat dihapus!');
        }

        $divisionName = $division->name;
        $division->delete();

        return back()->with('success', "Divisi {$divisionName} berhasil dihapus.");
    }
}
