<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\OrganizationLetter;
use Illuminate\Support\Facades\Storage;

class OrganizationLetterController extends Controller
{
    public function index(Request $request)
    {
        $query = OrganizationLetter::query();
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $letters = $query->latest('letter_date')->paginate(10);
        
        return view('kepengurusan.sekretaris.letters.index', compact('letters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:masuk,keluar',
            'letter_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'party' => 'nullable|string|max:255',
            'file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = $request->except('file');
        
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('organization_letters', 'public');
        }

        OrganizationLetter::create($data);

        return back()->with('success', 'Arsip surat berhasil ditambahkan.');
    }

    public function update(Request $request, OrganizationLetter $letter)
    {
        $request->validate([
            'type' => 'required|in:masuk,keluar',
            'letter_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'subject' => 'required|string|max:255',
            'party' => 'nullable|string|max:255',
            'file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = $request->except('file');

        if ($request->hasFile('file')) {
            if ($letter->file_path) {
                Storage::disk('public')->delete($letter->file_path);
            }
            $data['file_path'] = $request->file('file')->store('organization_letters', 'public');
        }

        $letter->update($data);

        return back()->with('success', 'Arsip surat berhasil diperbarui.');
    }

    public function destroy(OrganizationLetter $letter)
    {
        if ($letter->file_path) {
            Storage::disk('public')->delete($letter->file_path);
        }
        
        $letter->delete();

        return back()->with('success', 'Arsip surat berhasil dihapus.');
    }
}
