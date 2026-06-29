<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepanitiaan\EventCommittee;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\ProgressReport\WorkTask;

class MemberController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get user's assignments as CO Divisi
        $assignments = $user->eventCommittees()
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->with(['event', 'role', 'division'])
            ->get();

        $divisionsData = [];

        foreach ($assignments as $assignment) {
            // CO Divisi: fetch only their division
            $members = EventCommittee::where('event_id', $assignment->event_id)
                ->where('event_division_id', $assignment->event_division_id)
                ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
                ->with('user')
                ->get();

            // Attach tasks for each member
            foreach ($members as $member) {
                $member->tasks = WorkTask::where('event_id', $assignment->event_id)
                    ->where('event_division_id', $assignment->event_division_id)
                    ->where('assigned_to', $member->user_id)
                    ->orderBy('due_date', 'asc')
                    ->get();
            }

            $divisionsData[] = [
                'event' => $assignment->event,
                'division' => $assignment->division,
                'members' => $members
            ];
        }

        return view('kepanitiaan.co.members.index', compact('divisionsData'));
    }

    public function ketupelIndex()
    {
        $user = auth()->user();

        // Get user's assignments as Ketupel or Wakil Ketupel
        $assignments = $user->eventCommittees()
            ->whereHas('role', fn($q) => $q->whereIn('slug', ['ketua-pelaksana', 'wakil-ketua-pelaksana']))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->with(['event', 'role', 'division'])
            ->get();

        $divisionsData = [];

        foreach ($assignments as $assignment) {
            // Ketupel: fetch all divisions in this event
            $divisions = EventDivision::where('event_id', $assignment->event_id)->get();
            
            foreach ($divisions as $division) {
                $members = EventCommittee::where('event_id', $assignment->event_id)
                    ->where('event_division_id', $division->id)
                    ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
                    ->with('user')
                    ->get();
                
                // Attach tasks for each member
                foreach ($members as $member) {
                    $member->tasks = WorkTask::where('event_id', $assignment->event_id)
                        ->where('event_division_id', $division->id)
                        ->where('assigned_to', $member->user_id)
                        ->orderBy('due_date', 'asc')
                        ->get();
                }

                $divisionsData[] = [
                    'event' => $assignment->event,
                    'division' => $division,
                    'members' => $members
                ];
            }
        }

        return view('kepanitiaan.co.members.index', compact('divisionsData'));
    }
}
