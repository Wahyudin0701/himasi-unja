<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\VitalArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VitalArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = VitalArchive::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $archives = $query->latest('date')->paginate(12);

        return view('kepengurusan.sekretaris.archives.index', compact('archives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
            'file' => 'required|mimes:pdf,doc,docx,zip,rar|max:10240',
        ]);

        $path = $request->file('file')->store('vital_archives', 'public');

        VitalArchive::create([
            'title' => $request->title,
            'category' => $request->category,
            'date' => $request->date,
            'file_path' => $path,
            'uploaded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Arsip dokumen berhasil ditambahkan.');
    }

    public function update(Request $request, VitalArchive $archive)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'date' => 'required|date',
            'file' => 'nullable|mimes:pdf,doc,docx,zip,rar|max:10240',
        ]);

        $data = $request->only('title', 'category', 'date');

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($archive->file_path);
            $data['file_path'] = $request->file('file')->store('vital_archives', 'public');
        }

        $archive->update($data);

        return back()->with('success', 'Arsip dokumen berhasil diperbarui.');
    }

    public function destroy(VitalArchive $archive)
    {
        Storage::disk('public')->delete($archive->file_path);
        $archive->delete();

        return back()->with('success', 'Arsip dokumen berhasil dihapus.');
    }
}
