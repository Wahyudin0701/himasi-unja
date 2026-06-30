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

        // Ambil proker internal atau kolaborasi yang mana user adalah PIC-nya atau sebagai collaborator
        $prokers = WorkProgram::where(function($query) use ($user) {
                $query->where('pic_id', $user->id)
                      ->whereIn('type', ['internal', 'kolaborasi']);
            })
            ->orWhere(function($query) use ($user) {
                $query->whereHas('collaborators', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                })
                ->where('type', 'kolaborasi');
            })
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
            'status' => 'pending',
        ]);

        // Ubah status proker ke ongoing jika masih planning
        if ($proker->status === 'planning') {
            $proker->update(['status' => 'ongoing']);
        }

        return back()->with('success', 'Laporan progres berhasil diajukan dan menunggu reviu Kadiv.');
    }
}
