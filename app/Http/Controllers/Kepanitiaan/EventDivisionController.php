<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use Illuminate\Http\Request;

class EventDivisionController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        EventDivision::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Divisi kepanitiaan berhasil ditambahkan!');
    }

    public function update(Request $request, Event $event, EventDivision $division)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $division->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Divisi kepanitiaan berhasil diperbarui!');
    }

    public function destroy(Event $event, EventDivision $division)
    {
        if ($division->name === 'Tim Inti') {
            return redirect()->route('events.show', $event->id)->with('error', 'Tim Inti tidak dapat dihapus!');
        }

        $division->delete();
        return redirect()->route('events.show', $event->id)->with('success', 'Divisi kepanitiaan berhasil dihapus!');
    }
}
