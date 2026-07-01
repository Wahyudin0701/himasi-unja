<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\User;

class KadivProkerController extends Controller
{
    /**
     * Menampilkan daftar Program Kerja divisi untuk Kadiv
     */
    public function index()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            abort(404, 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->with(['division.workPrograms'])
            ->first();

        if (!$membership) {
            abort(403, 'Anda tidak terdaftar dalam divisi di periode aktif.');
        }

        $division = $membership->division;
        $prokers = $division->workPrograms;

        return view('kepengurusan.kadiv.proker.index', compact('user', 'division', 'prokers'));
    }

    /**
     * API: Mengambil daftar anggota sebuah divisi
     */
    public function getDivisionMembers(Division $division)
    {
        $members = User::whereHas('memberships', function($q) use ($division) {
            $q->where('division_id', $division->id);
        })->orderBy('name')->get(['id', 'name']);

        return response()->json($members);
    }

    /**
     * Menampilkan form tambah Program Kerja
     */
    public function create()
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        if (!$activePeriod) {
            abort(404, 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership) {
            abort(403, 'Anda tidak terdaftar dalam divisi di periode aktif.');
        }

        $division = $membership->division;
        
        $divisionMembers = User::whereHas('memberships', function($q) use ($division) {
            $q->where('division_id', $division->id);
        })->orderBy('name')->get();

        $allEligibleMembers = User::with(['memberships.division'])
            ->whereNotIn('global_role', ['super_admin', 'pembina', 'dp', 'kahim', 'wakahim', 'sekretaris', 'bendahara'])
            ->orderBy('name')
            ->get();
            
        $otherDivisions = Division::where('id', '!=', $division->id)
            ->where('period_id', $activePeriod->id)
            ->where('type', 'divisi')
            ->orderBy('name')
            ->get();
            
        $subDivisions = \App\Models\Kepengurusan\SubDivision::where('division_id', $division->id)->orderBy('name')->get();

        return view('kepengurusan.kadiv.proker.create', compact('division', 'divisionMembers', 'allEligibleMembers', 'otherDivisions', 'subDivisions'));
    }

    /**
     * Menyimpan Program Kerja baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:event,internal,kolaborasi',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'sub_division_id' => 'nullable|exists:sub_divisions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget_plan' => 'nullable|numeric|min:0',
            'ketupel_id' => 'nullable|required_if:type,event|exists:users,id',
            'waketupel_id' => 'nullable|required_if:type,event|exists:users,id',
            'pj_id' => 'nullable|required_if:type,internal,kolaborasi|exists:users,id',
            'partner_divisions' => 'nullable|array',
            'partner_divisions.*' => 'exists:divisions,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);
        
        $validated['status'] = 'planning';

        $division = $membership->division;

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $division, $activePeriod, $user) {
            $prokerData = collect($validated)->except(['ketupel_id', 'waketupel_id', 'pj_id', 'partner_divisions', 'collaborators'])->toArray();
            
            if ($validated['type'] === 'internal' || $validated['type'] === 'kolaborasi') {
                $prokerData['pic_id'] = $validated['pj_id'];
            } else {
                $prokerData['pic_id'] = $validated['ketupel_id'];
                $prokerData['budget_plan'] = null;
            }

            $proker = $division->workPrograms()->create($prokerData);

            if ($validated['type'] === 'kolaborasi') {
                if (!empty($validated['partner_divisions'])) {
                    $proker->partnerDivisions()->sync($validated['partner_divisions']);
                }
                if (!empty($validated['collaborators'])) {
                    $proker->collaborators()->sync($validated['collaborators']);
                }
            }

            if ($validated['type'] === 'event') {
                $event = \App\Models\Kepanitiaan\Event::create([
                    'period_id' => $activePeriod->id,
                    'work_program_id' => $proker->id,
                    'created_by' => $user->id,
                    'name' => $proker->name,
                    'description' => $proker->description,
                    'event_date' => $proker->start_date,
                    'end_date' => $proker->end_date,
                    'status' => 'planning',
                ]);

                // Role ID 1: Ketupel
                \App\Models\Kepanitiaan\EventCommittee::create([
                    'event_id' => $event->id,
                    'user_id' => $validated['ketupel_id'],
                    'committee_role_id' => 1,
                ]);

                // Role ID 2: Waketupel
                if ($validated['waketupel_id'] != $validated['ketupel_id']) {
                    \App\Models\Kepanitiaan\EventCommittee::create([
                        'event_id' => $event->id,
                        'user_id' => $validated['waketupel_id'],
                        'committee_role_id' => 2,
                    ]);
                }
            }
        });

        return redirect()->route('kepengurusan.kadiv.proker.index')->with('success', 'Program Kerja berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail Program Kerja
     */
    public function show(\App\Models\Kepengurusan\WorkProgram $proker)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->firstOrFail();

        // Ensure the proker belongs to the user's division
        if ($proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $proker->load(['pic']);
        
        $divisionMembers = \App\Models\User::whereHas('memberships', function($q) use ($membership) {
            $q->where('division_id', $membership->division_id);
        })->orderBy('name')->get();

        $logs = \App\Models\Kepengurusan\ProkerLog::where('work_program_id', $proker->id)
            ->with(['author'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('kepengurusan.kadiv.proker.show', compact('proker', 'divisionMembers', 'logs'));
    }

    /**
     * Menampilkan progres Program Kerja
     */
    public function progress(\App\Models\Kepengurusan\WorkProgram $proker)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->firstOrFail();

        // Ensure the proker belongs to the user's division
        if ($proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $event = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)
            ->with(['divisions.committees.user', 'divisions.committees.role'])
            ->first();

        // Fetch tasks
        $tasksQuery = \App\Models\ProgressReport\WorkTask::where('work_program_id', $proker->id);
        if ($event) {
            $tasksQuery->orWhere('event_id', $event->id);
        }
        $tasks = $tasksQuery->with(['assignee', 'assigner', 'eventDivision'])->get();

        // Calculate progress metrics
        $totalTasks = $tasks->count();
        $todoTasks = $tasks->where('status', 'todo')->count();
        $waitingTasks = $tasks->where('status', 'waiting')->count();
        $revisiTasks = $tasks->where('status', 'revisi')->count();
        $completedTasks = $tasks->where('status', 'completed')->count();

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Group tasks by status
        $tasksByStatus = [
            'todo' => $tasks->where('status', 'todo'),
            'waiting' => $tasks->where('status', 'waiting'),
            'revisi' => $tasks->where('status', 'revisi'),
            'completed' => $tasks->where('status', 'completed'),
        ];

        // Division progress
        $divisionProgress = collect();
        if ($event && $event->divisions) {
            foreach ($event->divisions as $div) {
                $divTasks = $tasks->where('event_division_id', $div->id);
                $divTotal = $divTasks->count();
                $divCompleted = $divTasks->where('status', 'completed')->count();
                $divPercentage = $divTotal > 0 ? round(($divCompleted / $divTotal) * 100) : 0;

                $co = $div->committees->where('role.slug', 'co-divisi')->first();

                $divisionProgress->push([
                    'division' => $div,
                    'co' => $co ? $co->user : null,
                    'total' => $divTotal,
                    'completed' => $divCompleted,
                    'percentage' => $divPercentage,
                ]);
            }
        }

        // Recent reports/activities
        $taskIds = $tasks->pluck('id')->toArray();
        $recentReports = \App\Models\ProgressReport\ProgressReport::whereIn('work_task_id', $taskIds)
            ->with(['reporter', 'task'])
            ->latest()
            ->take(5)
            ->get();

        return view('kepengurusan.kadiv.proker.progress', compact(
            'proker', 'event', 'tasks', 'totalTasks', 'todoTasks', 'waitingTasks', 'revisiTasks', 'completedTasks',
            'progressPercentage', 'tasksByStatus', 'divisionProgress', 'recentReports'
        ));
    }

    /**
     * Menampilkan progres rincian divisi dari event
     */
    public function divisionProgress(\App\Models\Kepengurusan\WorkProgram $proker, \App\Models\Kepanitiaan\EventDivision $division)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->firstOrFail();

        // Ensure the proker belongs to the user's division
        if ($proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $event = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)->firstOrFail();

        $members = \App\Models\Kepanitiaan\EventCommittee::where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->with('user')
            ->get();
            
        foreach ($members as $member) {
            $member->tasks = \App\Models\ProgressReport\WorkTask::where('event_id', $event->id)
                ->where('event_division_id', $division->id)
                ->where('assigned_to', $member->user_id)
                ->orderBy('due_date', 'asc')
                ->get();
        }

        $divisionsData = [
            [
                'event' => $event,
                'division' => $division,
                'members' => $members
            ]
        ];

        return view('kepengurusan.kadiv.proker.division-progress', compact('proker', 'divisionsData'));
    }

    /**
     * Menampilkan form edit Program Kerja
     */
    public function edit(\App\Models\Kepengurusan\WorkProgram $proker)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->firstOrFail();

        // Ensure the proker belongs to the user's division
        if ($proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $division = $membership->division;
        
        $divisionMembers = \App\Models\User::whereHas('memberships', function($q) use ($division) {
            $q->where('division_id', $division->id);
        })->orderBy('name')->get();

        $allEligibleMembers = User::with(['memberships.division'])
            ->whereNotIn('global_role', ['super_admin', 'pembina', 'dp', 'kahim', 'wakahim', 'sekretaris', 'bendahara'])
            ->orderBy('name')
            ->get();

        $otherDivisions = Division::where('id', '!=', $division->id)
            ->where('period_id', $activePeriod->id)
            ->where('type', 'divisi')
            ->orderBy('name')
            ->get();

        $ketupelId = null;
        $waketupelId = null;
        $pjId = $proker->pic_id;

        if ($proker->type === 'event') {
            $event = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)->first();
            if ($event) {
                $ketupel = \App\Models\Kepanitiaan\EventCommittee::where('event_id', $event->id)->where('committee_role_id', 1)->first();
                $waketupel = \App\Models\Kepanitiaan\EventCommittee::where('event_id', $event->id)->where('committee_role_id', 2)->first();
                $ketupelId = $ketupel ? $ketupel->user_id : null;
                $waketupelId = $waketupel ? $waketupel->user_id : null;
            }
        }

        $proker->load(['partnerDivisions', 'collaborators']);
        $subDivisions = \App\Models\Kepengurusan\SubDivision::where('division_id', $division->id)->orderBy('name')->get();
        return view('kepengurusan.kadiv.proker.edit', compact('proker', 'division', 'divisionMembers', 'allEligibleMembers', 'otherDivisions', 'ketupelId', 'waketupelId', 'pjId', 'subDivisions'));
    }

    /**
     * Memperbarui data Program Kerja
     */
    public function update(Request $request, \App\Models\Kepengurusan\WorkProgram $proker)
    {
        $user = auth()->user();
        $activePeriod = Period::where('is_active', true)->first();

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->firstOrFail();

        // Ensure the proker belongs to the user's division
        if ($proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:event,internal,kolaborasi',
            'description' => 'nullable|string',
            'objective' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'sub_division_id' => 'nullable|exists:sub_divisions,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'budget_plan' => 'nullable|numeric|min:0',
            'ketupel_id' => 'nullable|required_if:type,event|exists:users,id',
            'waketupel_id' => 'nullable|required_if:type,event|exists:users,id',
            'pj_id' => 'nullable|required_if:type,internal,kolaborasi|exists:users,id',
            'partner_divisions' => 'nullable|array',
            'partner_divisions.*' => 'exists:divisions,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
        ]);

        // Force type to remain unchanged to maintain data integrity
        $validated['type'] = $proker->type;

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $proker, $activePeriod, $user) {
            $prokerData = collect($validated)->except(['ketupel_id', 'waketupel_id', 'pj_id', 'partner_divisions', 'collaborators'])->toArray();
            
            if ($validated['type'] === 'internal' || $validated['type'] === 'kolaborasi') {
                $prokerData['pic_id'] = $validated['pj_id'];
            } else {
                $prokerData['pic_id'] = $validated['ketupel_id'];
                $prokerData['budget_plan'] = null;
            }

            $proker->update($prokerData);

            if ($validated['type'] === 'kolaborasi') {
                $proker->partnerDivisions()->sync($validated['partner_divisions'] ?? []);
                $proker->collaborators()->sync($validated['collaborators'] ?? []);
            } else {
                $proker->partnerDivisions()->detach();
                $proker->collaborators()->detach();
            }

            if ($validated['type'] === 'event') {
                $event = \App\Models\Kepanitiaan\Event::where('work_program_id', $proker->id)->first();
                if ($event) {
                    $event->update([
                        'name' => $proker->name,
                        'description' => $proker->description,
                        'event_date' => $proker->start_date,
                        'end_date' => $proker->end_date,
                    ]);

                    // Update Ketupel (Role ID 1)
                    if ($validated['ketupel_id']) {
                        \App\Models\Kepanitiaan\EventCommittee::updateOrCreate(
                            ['event_id' => $event->id, 'committee_role_id' => 1],
                            ['user_id' => $validated['ketupel_id']]
                        );
                    }

                    // Update Waketupel (Role ID 2)
                    if ($validated['waketupel_id']) {
                        \App\Models\Kepanitiaan\EventCommittee::updateOrCreate(
                            ['event_id' => $event->id, 'committee_role_id' => 2],
                            ['user_id' => $validated['waketupel_id']]
                        );
                    } else {
                        \App\Models\Kepanitiaan\EventCommittee::where('event_id', $event->id)
                            ->where('committee_role_id', 2)
                            ->delete();
                    }
                }
            }
        });

        return redirect()->route('kepengurusan.kadiv.proker.show', $proker->id)->with('success', 'Program Kerja berhasil diperbarui!');
    }

    public function cancel(Request $request, \App\Models\Kepengurusan\WorkProgram $proker)
    {
        $user = auth()->user();
        $activePeriod = \App\Models\Kepengurusan\Period::where('is_active', true)->first();

        if (!$activePeriod) {
            abort(403, 'Tidak ada periode aktif.');
        }

        $membership = $user->memberships()
            ->whereHas('division', fn($q) => $q->where('period_id', $activePeriod->id))
            ->first();

        if (!$membership) {
            abort(403, 'Akses ditolak.');
        }

        if ($proker->division_id !== $membership->division_id) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:1000'
        ]);

        $proker->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason']
        ]);

        return redirect()->back()->with('success', 'Program Kerja berhasil dibatalkan.');
    }
}
