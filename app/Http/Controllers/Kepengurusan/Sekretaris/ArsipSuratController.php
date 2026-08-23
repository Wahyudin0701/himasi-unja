<?php

namespace App\Http\Controllers\Kepengurusan\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\EventLetter;
use App\Models\Kepanitiaan\Event;
use Illuminate\Http\Request;

class ArsipSuratController extends Controller
{
    /**
     * Tampilkan semua pengajuan surat dari seluruh event (untuk Sekretaris HIMA).
     */
    public function index(Request $request)
    {
        $query = EventLetter::with(['event', 'submitter', 'reviewer'])
            ->orderByRaw("FIELD(status, 'pending', 'revision', 'approved')")
            ->orderBy('created_at', 'desc');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $letters = $query->get();
        $events  = Event::has('letters')->get();

        return view('pengurus.sekretaris.arsip_surat.index', compact('letters', 'events'));
    }

    /**
     * Sekretaris HIMA menyetujui pengajuan surat dan memberikan nomor surat.
     */
    public function approve(Request $request, EventLetter $letter)
    {
        $validated = $request->validate([
            'letter_number' => 'required|string|max:255',
            'letter_date'   => 'required|date',
            'type'          => 'required|in:masuk,keluar',
        ], [
            'letter_number.required' => 'Nomor surat wajib diisi.',
            'letter_date.required'   => 'Tanggal surat wajib diisi.',
            'type.required'          => 'Tipe surat wajib dipilih.',
        ]);

        $letter->update([
            'status'        => 'approved',
            'reviewed_by'   => auth()->id(),
            'review_notes'  => null,
            'approved_at'   => now(),
            'letter_number' => $validated['letter_number'],
            'letter_date'   => $validated['letter_date'],
            'type'          => $validated['type'],
        ]);

        return back()->with('success', 'Pengajuan surat telah disetujui dan nomor surat telah diberikan.');
    }

    /**
     * Sekretaris HIMA meminta revisi dengan memberikan catatan.
     */
    public function requestRevision(Request $request, EventLetter $letter)
    {
        $validated = $request->validate([
            'review_notes' => 'required|string',
        ], [
            'review_notes.required' => 'Catatan revisi wajib diisi.',
        ]);

        $letter->update([
            'status'       => 'revision',
            'reviewed_by'  => auth()->id(),
            'review_notes' => $validated['review_notes'],
        ]);

        return back()->with('success', 'Catatan revisi telah dikirim ke Sekpel event.');
    }
}
