<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepengurusan\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Event::with('period');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $events = $query->orderBy('event_date', 'desc')->paginate(9);
        
        return view('kepanitiaan.events.index', compact('events', 'status'));
    }

    public function create()
    {
        $activePeriod = Period::where('is_active', true)->first();
        $workPrograms = WorkProgram::where('period_id', $activePeriod?->id)->get();
        return view('kepanitiaan.events.create', compact('workPrograms', 'activePeriod'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'location' => 'nullable|string|max:255',
            'work_program_id' => 'nullable|exists:work_programs,id',
            'period_id' => 'required|exists:periods,id'
        ]);

        $event = new Event($request->all());
        $event->slug = Str::slug($request->name) . '-' . time();
        $event->status = 'planning';
        $event->save();

        // Create Default Tim Inti Division
        EventDivision::create([
            'event_id' => $event->id,
            'name' => 'Tim Inti',
            'description' => 'Ketua, Sekretaris, Bendahara Pelaksana'
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Event berhasil dibuat!');
    }

    public function show(Event $event)
    {
        $event->load(['period', 'workProgram', 'eventDivisions.committees.user', 'eventDivisions.committees.committeeRole']);
        
        // Group committees by type (inti, divisi)
        $timInti = $event->eventDivisions()->where('name', 'Tim Inti')->first();
        $divisiLain = $event->eventDivisions()->where('name', '!=', 'Tim Inti')->get();
        
        return view('kepanitiaan.events.show', compact('event', 'timInti', 'divisiLain'));
    }

    public function edit(Event $event)
    {
        $activePeriod = Period::where('is_active', true)->first();
        $workPrograms = WorkProgram::where('period_id', $activePeriod?->id)->get();
        return view('kepanitiaan.events.edit', compact('event', 'workPrograms'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:planning,ongoing,completed',
            'work_program_id' => 'nullable|exists:work_programs,id',
        ]);

        $event->update($request->all());

        return redirect()->route('events.show', $event->id)->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event berhasil dihapus!');
    }
}
