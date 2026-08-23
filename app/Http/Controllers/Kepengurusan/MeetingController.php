<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Meeting;
use App\Models\Kepengurusan\MeetingAttendance;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::latest('date')->latest('time')->paginate(12);
        return view('kepengurusan.sekretaris.meetings.index', compact('meetings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        $meeting = Meeting::create($request->all());

        // Otomatis buat daftar absen (semua pengurus default alpa)
        // Pengurus = user yang bukan super_admin
        $users = User::where('global_role', '!=', 'super_admin')->get();
        foreach ($users as $user) {
            MeetingAttendance::create([
                'meeting_id' => $meeting->id,
                'user_id' => $user->id,
                'status' => 'alpa'
            ]);
        }

        return redirect()->route('kepengurusan.sekretaris.meetings.show', $meeting)->with('success', 'Rapat berhasil dibuat.');
    }

    public function show(Meeting $meeting)
    {
        $activePeriod = \App\Models\Kepengurusan\Period::where('is_active', true)->first();
        
        $meeting->load(['attendances.user.memberships' => function($q) use ($activePeriod) {
            $q->whereHas('division', function($query) use ($activePeriod) {
                if ($activePeriod) {
                    $query->where('period_id', $activePeriod->id);
                }
            })->with('division');
        }]);

        $groupedAttendances = $meeting->attendances->groupBy(function ($attendance) {
            $membership = $attendance->user->memberships->first();
            return $membership && $membership->division ? $membership->division->name : 'Lainnya / Tanpa Divisi';
        })->sortKeys()->filter(function ($attendances, $divisionName) {
            return !in_array($divisionName, ['Dewan Penasehat', 'Dewan Pembina', 'Lainnya / Tanpa Divisi']);
        });

        $totalDivisions = $groupedAttendances->count();
        $presentDivisions = 0;
        foreach ($groupedAttendances as $divisionName => $attendances) {
            if ($attendances->where('status', 'hadir')->count() > 0) {
                $presentDivisions++;
            }
        }

        return view('kepengurusan.sekretaris.meetings.show', compact('meeting', 'groupedAttendances', 'totalDivisions', 'presentDivisions'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        $meeting->update($request->all());
        return back()->with('success', 'Detail rapat diperbarui.');
    }

    public function updateMinutes(Request $request, Meeting $meeting)
    {
        $request->validate([
            'minutes_text' => 'nullable|string',
            'minutes_file' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = $request->only('minutes_text');

        if ($request->hasFile('minutes_file')) {
            if ($meeting->minutes_file) {
                Storage::disk('public')->delete($meeting->minutes_file);
            }
            $data['minutes_file'] = $request->file('minutes_file')->store('meeting_minutes', 'public');
        }

        $meeting->update($data);
        return back()->with('success', 'Notulensi berhasil diperbarui.');
    }

    public function updateAttendance(Request $request, Meeting $meeting)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        foreach ($request->attendances as $attendanceId => $status) {
            MeetingAttendance::where('id', $attendanceId)->update(['status' => $status]);
        }

        return back()->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(Meeting $meeting)
    {
        if ($meeting->minutes_file) {
            Storage::disk('public')->delete($meeting->minutes_file);
        }
        $meeting->delete();

        return redirect()->route('kepengurusan.sekretaris.meetings.index')->with('success', 'Rapat berhasil dihapus.');
    }
}
