<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\SubDivision;
use Illuminate\Support\Str;

class SubDivisionController extends Controller
{
    public function index(Division $division)
    {
        $division->load('subDivisions.members.user', 'subDivisions.members.orgPosition');
        return view('super_admin.sub_divisions.index', compact('division'));
    }

    public function store(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        // Ensure unique slug per division
        if ($division->subDivisions()->where('slug', $slug)->exists()) {
            return back()->with('error', 'Bidang dengan nama tersebut sudah ada di divisi ini.');
        }

        $division->subDivisions()->create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('super_admin.members.index')->with('success', 'Bidang berhasil ditambahkan.');
    }
    public function update(Request $request, SubDivision $subDivision)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        // Ensure unique slug per division excluding current
        if ($subDivision->division->subDivisions()->where('slug', $slug)->where('id', '!=', $subDivision->id)->exists()) {
            return back()->with('error', 'Bidang dengan nama tersebut sudah ada di divisi ini.');
        }

        $subDivision->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->back()->with('success', 'Nama bidang berhasil diperbarui.');
    }

    public function destroy(SubDivision $subDivision)
    {
        // Check if there are members attached
        if ($subDivision->members()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus Bidang ini karena masih memiliki anggota yang terdaftar.');
        }

        $subDivision->delete();
        return redirect()->route('super_admin.members.index')->with('success', 'Bidang berhasil dihapus.');
    }
}
