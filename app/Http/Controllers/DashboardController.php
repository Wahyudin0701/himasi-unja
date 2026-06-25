<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('role', 'division');
        $roleName = strtolower($user->role->name ?? 'anggota');
        
        // Sementara memberikan koleksi kosong karena fitur Proker/Tugas belum diimplementasi di database
        $prokers = collect();
        $members = collect();
        $tasks = collect();
        $totalProker = 0;
        $activeProker = 0;
        $totalBudget = 0;

        if (in_array($roleName, ['bph', 'ketua', 'ketua umum', 'wakil ketua', 'sekretaris', 'bendahara'])) {
            return view('dashboard.executive', compact(
                'user', 'prokers', 'totalProker', 'activeProker', 'totalBudget'
            ));
        } elseif (str_contains($roleName, 'kadiv') || str_contains($roleName, 'kepala')) {
            return view('dashboard.kadiv', compact('user', 'prokers', 'members'));
        } else {
            return view('dashboard.anggota', compact('user', 'tasks'));
        }
    }
}
