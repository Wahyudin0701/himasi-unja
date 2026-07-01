<?php

namespace App\Http\Controllers\Kepanitiaan\CO;

use App\Http\Controllers\Controller;
use App\Models\Rab;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use Illuminate\Http\Request;

class RabController extends Controller
{
    public function index(Event $event, EventDivision $division)
    {
        $rabs = Rab::where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->get();
            
        $assignment = auth()->user()->eventCommittees()
            ->where('event_id', $event->id)
            ->where('event_division_id', $division->id)
            ->first();
            
        return view('kepanitiaan.co.rab.index', compact('event', 'division', 'rabs', 'assignment'));
    }

    public function store(Request $request, Event $event, EventDivision $division)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['event_id'] = $event->id;
        $validated['event_division_id'] = $division->id;
        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

        Rab::create($validated);

        return back()->with('success', 'Item RAB berhasil ditambahkan!');
    }

    public function update(Request $request, Event $event, EventDivision $division, Rab $rab)
    {
        if ($rab->event_division_id !== $division->id) {
            abort(403);
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $validated['total_price'] = $validated['quantity'] * $validated['unit_price'];

        $rab->update($validated);

        return back()->with('success', 'Item RAB berhasil diperbarui!');
    }

    public function destroy(Event $event, EventDivision $division, Rab $rab)
    {
        if ($rab->event_division_id !== $division->id) {
            abort(403);
        }

        $rab->delete();

        return back()->with('success', 'Item RAB berhasil dihapus!');
    }
}
