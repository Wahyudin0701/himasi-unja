<?php

namespace App\Http\Controllers\Kepengurusan\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepengurusan\ProkerLog;
use Illuminate\Support\Facades\Storage;

class AnggotaProkerController extends Controller
{
    /**
     * Tampilkan Dashboard Proker untuk Anggota Divisi.
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil proker internal atau kolaborasi yang mana user adalah PIC-nya
        $prokers = WorkProgram::where('pic_id', $user->id)
            ->whereIn('type', ['internal', 'kolaborasi'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalProkers = $prokers->count();
        $completedProkers = $prokers->where('status', 'completed')->count();
        $ongoingProkers = $prokers->where('status', 'ongoing')->count();
        $planningProkers = $prokers->where('status', 'planning')->count();
        
        return view('kepengurusan.anggota.proker.kanban', compact(
            'user', 'prokers', 'totalProkers', 'completedProkers', 'ongoingProkers', 'planningProkers'
        ));
    }

    /**
     * Tampilkan detail proker dan riwayat jurnal.
     */
    public function show(WorkProgram $proker)
    {
        $user = auth()->user();

        $isCollaborator = $proker->type === 'kolaborasi' && $proker->collaborators()->where('users.id', $user->id)->exists();
        if ($proker->pic_id !== $user->id && !$isCollaborator) {
            abort(403, 'Anda tidak memiliki akses ke program kerja ini.');
        }

        $logs = $proker->logs()->orderBy('created_at', 'desc')->get();

        return view('kepengurusan.anggota.proker.show', compact('user', 'proker', 'logs'));
    }

    /**
     * Tampilkan halaman jurnal progres.
     */
    public function progress(WorkProgram $proker)
    {
        $user = auth()->user();

        $isCollaborator = $proker->type === 'kolaborasi' && $proker->collaborators()->where('users.id', $user->id)->exists();
        if ($proker->pic_id !== $user->id && !$isCollaborator) {
            abort(403, 'Anda tidak memiliki akses ke program kerja ini.');
        }

        $logs = $proker->logs()->orderBy('created_at', 'desc')->get();

        return view('kepengurusan.anggota.proker.progress', compact('user', 'proker', 'logs'));
    }

    /**
     * Ajukan jurnal laporan progres baru
     */
    public function storeLog(Request $request, WorkProgram $proker)
    {
        $user = auth()->user();
        
        $isCollaborator = $proker->type === 'kolaborasi' && $proker->collaborators()->where('users.id', $user->id)->exists();
        if ($proker->pic_id !== $user->id && !$isCollaborator) {
            abort(403, 'Anda tidak memiliki akses ke proker ini.');
        }

        $validated = $request->validate([
            'content' => 'required|string',
            'progress_update' => 'required|integer|min:0|max:100',
            'attachment' => 'nullable|file|max:10240',
            'link' => 'nullable|url',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('proker_logs', 'public');
        }

        ProkerLog::create([
            'work_program_id' => $proker->id,
            'author_id' => $user->id,
            'content' => $validated['content'],
            'progress_update' => $validated['progress_update'],
            'attachment' => $attachmentPath,
            'link' => $validated['link'] ?? null,
            'status' => 'approved',
        ]);

        // Ubah status proker ke ongoing jika masih planning
        if ($proker->status === 'planning') {
            $proker->update(['status' => 'ongoing']);
        }

        return redirect()->route('kepengurusan.anggota.proker.show', $proker->id)->with('success', 'Laporan progres berhasil ditambahkan.');
    }
}
