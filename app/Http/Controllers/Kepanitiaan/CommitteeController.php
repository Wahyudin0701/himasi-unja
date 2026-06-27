<?php

namespace App\Http\Controllers\Kepanitiaan;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventCommittee;
use App\Models\Kepanitiaan\CommitteeRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CommitteeController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_division_id' => 'required|exists:event_divisions,id',
            'committee_role_id' => 'required|exists:committee_roles,id',
        ]);

        // Check if user is already in this division for this event
        $exists = EventCommittee::where('user_id', $request->user_id)
            ->where('event_division_id', $request->event_division_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'User sudah terdaftar di divisi ini!');
        }

        EventCommittee::create([
            'user_id' => $request->user_id,
            'event_division_id' => $request->event_division_id,
            'committee_role_id' => $request->committee_role_id,
            'joined_at' => now(),
            'is_active' => true
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Panitia berhasil ditambahkan!');
    }

    public function storeVolunteer(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'event_division_id' => 'required|exists:event_divisions,id',
            'committee_role_id' => 'required|exists:committee_roles,id',
        ]);

        // Create new volunteer user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(10)), // Default random password
            'phone' => $request->phone,
        ]);

        EventCommittee::create([
            'user_id' => $user->id,
            'event_division_id' => $request->event_division_id,
            'committee_role_id' => $request->committee_role_id,
            'joined_at' => now(),
            'is_active' => true
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Volunteer baru berhasil didaftarkan dan ditambahkan ke panitia!');
    }

    public function destroy(Event $event, EventCommittee $committee)
    {
        $committee->delete();
        return redirect()->route('events.show', $event->id)->with('success', 'Panitia berhasil dihapus!');
    }
}
