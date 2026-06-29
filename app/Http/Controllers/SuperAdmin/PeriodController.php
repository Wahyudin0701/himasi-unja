<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\OrgPosition;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::orderBy('start_date', 'desc')->get();
        return view('super_admin.periods.index', compact('periods'));
    }

    public function create()
    {
        $currentPeriod = Period::where('is_active', true)->first();
        
        if ($currentPeriod && preg_match('/^(\d{4})-(\d{4})$/', $currentPeriod->name, $matches)) {
            $startYear = intval($matches[1]) + 1;
            $endYear = intval($matches[2]) + 1;
            $nextPeriodName = "$startYear-$endYear";
        } else {
            $currentYear = intval(date('Y'));
            $nextPeriodName = $currentYear . '-' . ($currentYear + 1);
        }

        return view('super_admin.periods.create', compact('currentPeriod', 'nextPeriodName'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () {
            // 1. Archive the current period
            $oldPeriod = Period::where('is_active', true)->first();
            
            // Calculate new period name
            if ($oldPeriod && preg_match('/^(\d{4})-(\d{4})$/', $oldPeriod->name, $matches)) {
                $startYear = intval($matches[1]) + 1;
                $endYear = intval($matches[2]) + 1;
                $newPeriodName = "$startYear-$endYear";
            } else {
                $currentYear = intval(date('Y'));
                $newPeriodName = $currentYear . '-' . ($currentYear + 1);
            }

            if ($oldPeriod) {
                $oldPeriod->update([
                    'is_active' => false,
                    'archived_at' => now(),
                    'end_date' => now()->toDateString(),
                ]);
            }

            // 2. Deactivate old users EXCEPT sekretaris and super_admin
            User::whereNotIn('global_role', ['sekretaris', 'super_admin'])
                ->update(['is_active' => false]);

            // 3. Create the new period
            $newPeriod = Period::create([
                'name' => $newPeriodName,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addYear()->toDateString(),
                'is_active' => true,
            ]);

            // 4. If old period exists, copy divisions and create placeholders
            if ($oldPeriod) {
                $oldDivisions = Division::where('period_id', $oldPeriod->id)->get();
                
                foreach ($oldDivisions as $oldDiv) {
                    // Copy division
                    $newDiv = Division::create([
                        'period_id' => $newPeriod->id,
                        'name' => $oldDiv->name,
                        'slug' => $oldDiv->slug . '-' . time(), // unique slug
                        'type' => $oldDiv->type,
                        'description' => $oldDiv->description,
                    ]);

                    // Copy sub-divisions (bidang)
                    $oldSubDivs = \App\Models\Kepengurusan\SubDivision::where('division_id', $oldDiv->id)->get();
                    foreach ($oldSubDivs as $oldSubDiv) {
                        \App\Models\Kepengurusan\SubDivision::create([
                            'division_id' => $newDiv->id,
                            'name' => $oldSubDiv->name,
                            'slug' => $oldSubDiv->slug . '-' . time(),
                        ]);
                    }

                    // Create placeholders based on division type
                    if ($newDiv->type === 'bph') {
                        // Create Kahim, Wakahim, Bendahara
                        $bphPositions = ['Ketua Himpunan', 'Wakil Ketua Himpunan', 'Bendahara I', 'Bendahara II'];
                        foreach ($bphPositions as $pos) {
                            $this->createPlaceholderMember($newDiv->id, $pos, $newPeriod->name);
                        }
                    } elseif ($newDiv->type === 'divisi') {
                        // Create Kadiv, Wakadiv
                        $divPositions = ['Ketua Divisi ' . $newDiv->name, 'Wakil Ketua Divisi ' . $newDiv->name];
                        foreach ($divPositions as $pos) {
                            $this->createPlaceholderMember($newDiv->id, $pos, $newPeriod->name);
                        }
                    }
                }
            } else {
                // Initial setup if no old period exists (fallback)
                // Just create standard divisions
                $standardDivs = [
                    ['name' => 'Badan Pengurus Harian', 'type' => 'bph', 'slug' => 'bph-'.time()],
                    ['name' => 'Dewan Pembina', 'type' => 'pembina', 'slug' => 'pembina-'.time()],
                    ['name' => 'Dewan Penasihat', 'type' => 'dp', 'slug' => 'dp-'.time()],
                    ['name' => 'Hubungan Masyarakat', 'type' => 'divisi', 'slug' => 'humas-'.time()],
                    ['name' => 'Riset dan Teknologi', 'type' => 'divisi', 'slug' => 'ristek-'.time()],
                    ['name' => 'Dana dan Usaha', 'type' => 'divisi', 'slug' => 'danus-'.time()],
                    ['name' => 'Media dan Informasi', 'type' => 'divisi', 'slug' => 'mediasi-'.time()],
                    ['name' => 'Minat dan Bakat', 'type' => 'divisi', 'slug' => 'mdb-'.time()],
                    ['name' => 'Pengembangan Sumber Daya Anggota', 'type' => 'divisi', 'slug' => 'psda-'.time()],
                    ['name' => 'Sosial dan Agama', 'type' => 'divisi', 'slug' => 'sosgam-'.time()],
                    ['name' => 'Pengawasan dan Penyelesaian Masalah', 'type' => 'divisi', 'slug' => 'ppm-'.time()],
                ];
                $defaultSubDivs = [
                    'Hubungan Masyarakat' => ['Eksternal', 'Internal', 'Manajemen Sosial Media'],
                    'Riset dan Teknologi' => ['Riset', 'Teknologi'],
                    'Dana dan Usaha' => ['Sosial dan Branding', 'Kewirausahaan'],
                    'Media dan Informasi' => ['Desain', 'Videografi', 'Fotografi'],
                    'Minat dan Bakat' => ['Seni', 'Penyaluran bakat', 'Sport & E-sport'],
                    'Pengembangan Sumber Daya Anggota' => ['Kolaborasi Event', 'Pengelolaan & Konsolidasi Anggota'],
                    'Pengawasan dan Penyelesaian Masalah' => ['Pengelolaan Data', 'Pengawasan'],
                    'Sosial dan Agama' => ['Sosial', 'Agama'],
                ];
                
                foreach ($standardDivs as $d) {
                    $newDiv = Division::create([
                        'period_id' => $newPeriod->id,
                        'name' => $d['name'],
                        'slug' => $d['slug'],
                        'type' => $d['type'],
                        'description' => 'Otomatis dibuat',
                    ]);
                    
                    // Create default sub divisions
                    if (isset($defaultSubDivs[$newDiv->name])) {
                        foreach ($defaultSubDivs[$newDiv->name] as $subName) {
                            \App\Models\Kepengurusan\SubDivision::create([
                                'division_id' => $newDiv->id,
                                'name' => $subName,
                                'slug' => \Illuminate\Support\Str::slug($subName) . '-' . time(),
                            ]);
                        }
                    }
                    
                    if ($d['type'] === 'bph') {
                        $bphPositions = ['Ketua Himpunan', 'Wakil Ketua Himpunan', 'Sekretaris I', 'Sekretaris II', 'Bendahara I', 'Bendahara II'];
                        foreach ($bphPositions as $pos) {
                            $this->createPlaceholderMember($newDiv->id, $pos, $newPeriod->name);
                        }
                    } elseif ($d['type'] === 'divisi') {
                        $divPositions = ['Ketua Divisi', 'Wakil Ketua Divisi'];
                        foreach ($divPositions as $pos) {
                            $this->createPlaceholderMember($newDiv->id, $pos, $newPeriod->name);
                        }
                    }
                }
            }
        });

        return redirect()->route('super_admin.periods.index')->with('success', 'Periode baru berhasil dibuat dan data lama telah diarsipkan.');
    }

    public function show(Period $period)
    {
        return view('super_admin.periods.show', compact('period'));
    }

    public function structure(Period $period)
    {
        $pembina = Division::where('period_id', $period->id)->where('type', 'pembina')
            ->with(['members.user', 'members.orgPosition'])
            ->first();

        $dp = Division::where('period_id', $period->id)->where('type', 'dp')
            ->with(['members.user', 'members.orgPosition'])
            ->first();

        $bph = Division::where('period_id', $period->id)->where('type', 'bph')
            ->with(['members.user', 'members.orgPosition'])
            ->first();
            
        $divisions = Division::where('period_id', $period->id)->where('type', 'divisi')
            ->with(['members.user', 'members.orgPosition', 'members.subDivision'])
            ->get();
            
        return view('super_admin.periods.structure', compact('period', 'pembina', 'dp', 'bph', 'divisions'));
    }

    public function performance(Period $period)
    {
        $divisions = Division::where('period_id', $period->id)->where('type', 'divisi')->get();
        return view('super_admin.periods.performance-index', compact('period', 'divisions'));
    }

    public function performanceDivision(Period $period, Division $division)
    {
        if ($division->period_id !== $period->id) abort(404);
        
        $prokers = WorkProgram::where('division_id', $division->id)->with('pic')->get();
        return view('super_admin.periods.performance-division', compact('period', 'division', 'prokers'));
    }

    public function performanceProker(Period $period, WorkProgram $proker)
    {
        if ($proker->division->period_id !== $period->id) abort(404);
        
        $proker->load(['division', 'pic', 'tasks.pic']);
        return view('super_admin.periods.performance-proker', compact('period', 'proker'));
    }

    private function createPlaceholderMember($divisionId, $positionName, $periodName)
    {
        // Find or create OrgPosition
        $slug = Str::slug($positionName);
        $orgPosition = OrgPosition::firstOrCreate(
            ['slug' => $slug],
            ['name' => $positionName, 'level' => 1]
        );

        // Create Placeholder User
        $placeholderName = "[$positionName $periodName]";
        $user = User::create([
            'name' => $placeholderName,
            'email' => 'placeholder.' . uniqid() . '@himasi.unja.ac.id',
            'password' => Hash::make(Str::random(16)), // Unusable password
            'global_role' => 'anggota', // Will be edited later
            'is_active' => true, // Make active so it shows up in lists
        ]);

        // Define specific global roles for placeholder based on position name
        $posLower = strtolower($positionName);
        if (str_contains($posLower, 'ketua himpunan') && !str_contains($posLower, 'wakil')) {
            $user->global_role = 'kahim';
        } elseif (str_contains($posLower, 'wakil ketua himpunan')) {
            $user->global_role = 'wakahim';
        } elseif (str_contains($posLower, 'bendahara')) {
            $user->global_role = 'bendahara';
        } elseif (str_contains($posLower, 'sekretaris')) {
            $user->global_role = 'sekretaris';
        } elseif (str_contains($posLower, 'ketua divisi') && !str_contains($posLower, 'wakil')) {
            $user->global_role = 'kadiv';
        } elseif (str_contains($posLower, 'wakil ketua divisi')) {
            $user->global_role = 'kadiv'; // Wakil also has Kadiv access in this system for now
        }
        $user->save();

        // Attach to Member
        \App\Models\Kepengurusan\Member::create([
            'user_id' => $user->id,
            'division_id' => $divisionId,
            'org_position_id' => $orgPosition->id,
            'status' => 'aktif',
        ]);
    }
}
