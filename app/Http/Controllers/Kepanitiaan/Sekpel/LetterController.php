<?php

namespace App\Http\Controllers\Kepanitiaan\Sekpel;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LetterController extends Controller
{
    /**
     * Tampilkan daftar pengajuan surat untuk event ini.
     */
    public function index(Event $event)
    {
        $letters = EventLetter::where('event_id', $event->id)
            ->with(['submitter', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepanitiaan.sekpel.letters.index', compact('event', 'letters'));
    }

    /**
     * Ajukan permohonan surat baru ke Sekretaris HIMA.
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'party'   => 'nullable|string|max:255',
            'notes'   => 'nullable|string',
            'file'    => 'required|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'subject.required' => 'Perihal surat wajib diisi.',
            'file.required'    => 'File surat draft wajib dilampirkan.',
            'file.mimes'       => 'Format file harus PDF atau Word (doc/docx).',
            'file.max'         => 'Ukuran file maksimal 5MB.',
        ]);

        // Simpan file lampiran
        $filePath = $request->file('file')->store('event-letters', 'public');

        EventLetter::create([
            'event_id'     => $event->id,
            'submitted_by' => auth()->id(),
            'file_path'    => $filePath,
            'status'       => 'pending',
            'subject'      => $validated['subject'],
            'party'        => $validated['party'] ?? null,
            'notes'        => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Permohonan surat berhasil diajukan. Menunggu review dari Sekretaris HIMA.');
    }

    /**
     * Hapus pengajuan (hanya bisa jika masih pending atau revision).
     */
    public function destroy(Event $event, EventLetter $letter)
    {
        if ($letter->event_id !== $event->id) {
            abort(404);
        }

        if ($letter->isApproved()) {
            return back()->with('error', 'Pengajuan yang sudah disetujui tidak dapat dihapus.');
        }

        // Hapus file lampiran
        if ($letter->file_path) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();

        return back()->with('success', 'Pengajuan surat berhasil dibatalkan.');
    }
}
