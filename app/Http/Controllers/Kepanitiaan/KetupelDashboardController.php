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

        $allUsers = \App\Models\User::orderBy('name')->get();
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
            'name' => 'required_without:user_id|string|max:255',
            'email' => 'required_without:user_id|email|max:255',
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
}
