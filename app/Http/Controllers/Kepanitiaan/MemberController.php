<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepanitiaan\EventCommittee;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\ProgressReport\WorkTask;

class MemberController extends Controller
{
    public function index(\App\Models\Kepanitiaan\Event $event, \App\Models\Kepanitiaan\EventDivision $division)
    {
        $user = auth()->user();

        if (!$user->eventCommittees()->where('event_id', $event->id)->where('event_division_id', $division->id)->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))->exists()) {
            abort(403, 'Anda bukan Koordinator di divisi ini.');
        }

        $divisionsData = [];

        $members = EventCommittee::where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->with('user')
            ->get();

        foreach ($members as $member) {
            $member->tasks = WorkTask::where('event_id', $event->id)
                ->where('event_division_id', $division->id)
                ->where('assigned_to', $member->user_id)
                ->orderBy('due_date', 'asc')
                ->get();
        }

        $co = EventCommittee::where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->with('user')
            ->first();

        $divisionsData[] = [
            'event' => $event,
            'division' => $division,
            'co' => $co,
            'members' => $members
        ];

        $viewEvent = $event;

        return view('kepanitiaan.co.members.index', compact('divisionsData', 'viewEvent'));
    }

    public function ketupelIndex(\App\Models\Kepanitiaan\Event $event)
    {
        $user = auth()->user();

        $ketupelCommittee = $user->eventCommittees()->where('event_id', $event->id)->whereHas('role', fn($q) => $q->whereIn('slug', ['ketua-pelaksana', 'wakil-ketua-pelaksana']))->first();
        if (!$ketupelCommittee) {
            abort(403, 'Anda bukan Ketua/Wakil Pelaksana di acara ini.');
        }
        $userRoleName = $ketupelCommittee->role->name;

        $divisionsData = [];
        $divisions = EventDivision::where('event_id', $event->id)->get();
        
        foreach ($divisions as $div) {
            $members = EventCommittee::where('event_id', $event->id)
                ->where('event_division_id', $div->id)
                ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
                ->with('user')
                ->get();
            
            foreach ($members as $member) {
                $member->tasks = WorkTask::where('event_id', $event->id)
                    ->where('event_division_id', $div->id)
                    ->where('assigned_to', $member->user_id)
                    ->orderBy('due_date', 'asc')
                    ->get();
            }

            $co = EventCommittee::where('event_id', $event->id)
                ->where('event_division_id', $div->id)
                ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
                ->with('user')
                ->first();

            $divisionsData[] = [
                'event' => $event,
                'division' => $div,
                'co' => $co,
                'members' => $members
            ];
        }

        $viewEvent = $event;

        return view('kepanitiaan.co.members.index', compact('divisionsData', 'viewEvent', 'userRoleName'));
    }

    public function showTask(\App\Models\ProgressReport\WorkTask $task)
    {
        $user = auth()->user();
        
        $ketupelCommittee = $user->eventCommittees()->where('event_id', $task->event_id)->whereHas('role', fn($q) => $q->whereIn('slug', ['ketua-pelaksana', 'wakil-ketua-pelaksana']))->first();
        if (!$ketupelCommittee) {
            abort(403, 'Akses ditolak. Anda bukan Ketua/Wakil Pelaksana di acara ini.');
        }

        $latestReport = $task->reports()->latest()->first();

        return view('kepanitiaan.ketupel.tasks_detail', compact('task', 'latestReport'));
    }
}
