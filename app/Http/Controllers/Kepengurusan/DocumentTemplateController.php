<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\DocumentTemplate;
use Illuminate\Support\Facades\Storage;

class DocumentTemplateController extends Controller
{
    public function index()
    {
        $templates = DocumentTemplate::latest()->paginate(12);
        return view('kepengurusan.sekretaris.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $path = $request->file('file')->store('document_templates', 'public');

        DocumentTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'file_path' => $path,
        ]);

        return back()->with('success', 'Templat berhasil ditambahkan.');
    }

    public function update(Request $request, DocumentTemplate $template)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $data = $request->only('name', 'description');

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($template->file_path);
            $data['file_path'] = $request->file('file')->store('document_templates', 'public');
        }

        $template->update($data);

        return back()->with('success', 'Templat berhasil diperbarui.');
    }

    public function destroy(DocumentTemplate $template)
    {
        Storage::disk('public')->delete($template->file_path);
        $template->delete();

        return back()->with('success', 'Templat berhasil dihapus.');
    }
}
