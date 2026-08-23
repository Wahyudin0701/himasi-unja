<?php

namespace App\Http\Controllers\Kepanitiaan\Sekpel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventMeeting;
use App\Models\Kepanitiaan\EventMeetingAttendance;
use Illuminate\Support\Facades\Storage;

class EventMeetingController extends Controller
{
    /**
     * Tampilkan daftar rapat kepanitiaan
     */
    public function index(Event $event)
    {
        $meetings = EventMeeting::where('event_id', $event->id)
            ->latest('date')
            ->latest('time')
            ->paginate(12);

        return view('kepanitiaan.sekpel.meetings.index', compact('event', 'meetings'));
    }

    /**
     * Simpan jadwal rapat kepanitiaan baru
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'time'        => 'required',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $meeting = EventMeeting::create([
            'event_id'    => $event->id,
            'title'       => $request->title,
            'date'        => $request->date,
            'time'        => $request->time,
            'location'    => $request->location,
            'description' => $request->description,
            'status'      => 'scheduled',
        ]);

        // Otomatis buat daftar absen untuk semua anggota panitia di event ini
        $committees = $event->committees()->with('user')->get();
        foreach ($committees as $committee) {
            if ($committee->user_id) {
                // Hindari duplikasi jika ada user yang rangkap jabatan di satu event
                EventMeetingAttendance::firstOrCreate([
                    'event_meeting_id' => $meeting->id,
                    'user_id'          => $committee->user_id,
                ], [
                    'status' => 'alpa'
                ]);
            }
        }

        $routeBase = request()->routeIs('kepanitiaan.sekpel.*') ? 'kepanitiaan.sekpel.events.meetings' : 'kepanitiaan.ketupel.events.meetings';

        return redirect()->route($routeBase . '.show', ['event' => $event->id, 'meeting' => $meeting->id])
            ->with('success', 'Rapat kepanitiaan berhasil dijadwalkan.');
    }

    /**
     * Tampilkan detail rapat (absensi & notulensi)
     */
    public function show(Event $event, EventMeeting $meeting)
    {
        if ($meeting->event_id !== $event->id) {
            abort(404);
        }

        // Ambil data absensi beserta informasi posisi/divisi di kepanitiaan
        $attendances = $meeting->attendances()->with(['user.eventCommittees' => function ($query) use ($event) {
            $query->where('event_id', $event->id)->with('division', 'role');
        }])->get();

        // Kelompokkan absensi berdasarkan divisi event
        $groupedAttendances = $attendances->groupBy(function ($attendance) {
            $committee = $attendance->user->eventCommittees->first();
            if ($committee) {
                if ($committee->event_division_id && $committee->division) {
                    return $committee->division->name;
                } elseif ($committee->role) {
                    return 'Panitia Inti';
                }
            }
            return 'Lainnya';
        })->sortKeys();

        $totalGroups = $groupedAttendances->count();
        $presentGroups = 0;
        foreach ($groupedAttendances as $groupName => $atts) {
            if ($atts->where('status', 'hadir')->count() > 0) {
                $presentGroups++;
            }
        }

        return view('kepanitiaan.sekpel.meetings.show', compact('event', 'meeting', 'groupedAttendances', 'totalGroups', 'presentGroups'));
    }

    /**
     * Update detail rapat
     */
    public function update(Request $request, Event $event, EventMeeting $meeting)
    {
        if ($meeting->event_id !== $event->id) {
            abort(404);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'time'        => 'required',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        $meeting->update($request->all());
        return back()->with('success', 'Detail rapat berhasil diperbarui.');
    }

    /**
     * Update absensi rapat
     */
    public function updateAttendance(Request $request, Event $event, EventMeeting $meeting)
    {
        if ($meeting->event_id !== $event->id) {
            abort(404);
        }

        $request->validate([
            'attendances'   => 'required|array',
            'attendances.*' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        foreach ($request->attendances as $attendanceId => $status) {
            EventMeetingAttendance::where('id', $attendanceId)
                ->where('event_meeting_id', $meeting->id)
                ->update(['status' => $status]);
        }

        return back()->with('success', 'Kehadiran rapat berhasil disimpan.');
    }

    /**
     * Update notulensi rapat
     */
    public function updateMinutes(Request $request, Event $event, EventMeeting $meeting)
    {
        if ($meeting->event_id !== $event->id) {
            abort(404);
        }

        $request->validate([
            'minutes'      => 'nullable|string',
            'minutes_file' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = $request->only('minutes');

        if ($request->hasFile('minutes_file')) {
            if ($meeting->minutes_file) {
                Storage::disk('public')->delete($meeting->minutes_file);
            }
            $data['minutes_file'] = $request->file('minutes_file')->store('event-meeting-minutes', 'public');
        }

        $meeting->update($data);
        return back()->with('success', 'Notulensi rapat berhasil disimpan.');
    }

    /**
     * Hapus rapat
     */
    public function destroy(Event $event, EventMeeting $meeting)
    {
        if ($meeting->event_id !== $event->id) {
            abort(404);
        }

        if ($meeting->minutes_file) {
            Storage::disk('public')->delete($meeting->minutes_file);
        }
        $meeting->delete();

        $routeBase = request()->routeIs('kepanitiaan.sekpel.*') ? 'kepanitiaan.sekpel.events.meetings' : 'kepanitiaan.ketupel.events.meetings';

        return redirect()->route($routeBase . '.index', $event)->with('success', 'Jadwal rapat berhasil dihapus.');
    }
}
