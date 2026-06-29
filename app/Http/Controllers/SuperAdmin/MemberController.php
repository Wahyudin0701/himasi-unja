<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\Member;
use App\Models\Kepengurusan\OrgPosition;
use App\Models\User;
use App\Models\Kepanitiaan\EventCommittee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    private function determineGlobalRole($positionName) {
        $posLower = strtolower($positionName);
        if (str_contains($posLower, 'ketua himpunan') && !str_contains($posLower, 'wakil')) {
            return 'kahim';
        } elseif (str_contains($posLower, 'wakil ketua himpunan')) {
            return 'wakahim';
        } elseif (str_contains($posLower, 'bendahara')) {
            return 'bendahara';
        } elseif (str_contains($posLower, 'sekretaris')) {
            return 'sekretaris';
        } elseif (str_contains($posLower, 'ketua divisi') && !str_contains($posLower, 'wakil')) {
            return 'kadiv';
        } elseif (str_contains($posLower, 'wakil ketua divisi')) {
            return 'kadiv'; // Wakil also has Kadiv access
        }
        return 'anggota';
    }

    public function index(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        
        $divisions = Division::where('period_id', $activePeriod->id)
            ->where('type', 'divisi')
            ->get();
            
        // BPH Members
        $bphMembers = Member::whereHas('division', function ($q) use ($activePeriod) {
            $q->where('period_id', $activePeriod->id)->where('type', 'bph');
        })->with(['user', 'division', 'orgPosition'])->get();
        
        // Divisi Members Grouped by Division
        $divisiQuery = Division::where('period_id', $activePeriod->id)
            ->where('type', 'divisi')
            ->with(['members' => function($q) {
                $q->with(['user', 'orgPosition', 'subDivision']);
            }]);
        
        if ($request->has('division_id') && $request->division_id != '') {
            $divisiQuery->where('id', $request->division_id);
        }
        
        // Pembina Members
        $pembinaDivision = Division::where('period_id', $activePeriod->id)->where('type', 'pembina')->first();
        $pembinaMembers = Member::where('division_id', $pembinaDivision?->id)->with(['user', 'division', 'orgPosition'])->get();

        // DP Members
        $dpDivision = Division::where('period_id', $activePeriod->id)->where('type', 'dp')->first();
        $dpMembers = Member::where('division_id', $dpDivision?->id)->with(['user', 'division', 'orgPosition'])->get();

        $divisiGrouped = $divisiQuery->get();
        
        return view('super_admin.members.index', compact('pembinaDivision', 'dpDivision', 'pembinaMembers', 'dpMembers', 'bphMembers', 'divisiGrouped', 'divisions', 'activePeriod'));
    }

    public function create()
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        $divisions = Division::where('period_id', $activePeriod->id)->with(['subDivisions', 'members.orgPosition'])->get();
        $positions = OrgPosition::orderBy('name')->get();
        
        return view('super_admin.members.create', compact('divisions', 'positions', 'activePeriod'));
    }

    public function createPembina()
    {
        return view('super_admin.members.create_pembina');
    }

    public function storePembina(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:50|unique:users,nim', // We store NIP in 'nim' column
            'email_prefix' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $fullEmail = strtolower($validated['email_prefix']) . '@himasi.unja.ac.id';
        if (User::where('email', $fullEmail)->exists()) {
            return back()->withErrors(['email_prefix' => 'Email ini sudah terdaftar.'])->withInput();
        }

        DB::transaction(function () use ($validated, $fullEmail, $activePeriod) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $fullEmail,
                'nim' => $validated['nip'], // Store NIP in nim
                'angkatan' => '-', // Not applicable for Pembina
                'password' => Hash::make($validated['password']), // Custom password
                'global_role' => 'pembina',
                'avatar' => $this->processAvatarBase64(request()),
            ]);
            
            // Find or create Pembina position and division
            $pembinaDiv = Division::firstOrCreate(
                ['period_id' => $activePeriod->id, 'type' => 'pembina'],
                ['name' => 'Dewan Pembina', 'slug' => 'pembina-' . $activePeriod->id, 'description' => 'Otomatis dibuat']
            );
            $pembinaPos = OrgPosition::firstOrCreate(['slug' => 'pembina'], ['name' => 'Pembina', 'level' => 0]);
            
            Member::create([
                'user_id' => $user->id,
                'division_id' => $pembinaDiv->id,
                'org_position_id' => $pembinaPos->id,
            ]);
        });
        
        return redirect(route('super_admin.members.index') . '#section-pembina')
                         ->with('success', 'Pembina berhasil ditambahkan!');
    }

    public function createDp()
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        $dpPositions = [
            'Ketua Penasehat',
            'Penasehat Minat dan Bakat',
            'Penasehat Sosial dan Agama',
            'Penasehat Pengembangan Sumber Daya Anggota',
            'Penasehat Pengawasan dan Penyelesaian Masalah',
            'Penasehat Dana dan Usaha',
            'Penasehat Hubungan Masyarakat',
            'Penasehat Media dan Informasi',
            'Penasehat Riset dan Teknologi'
        ];
        
        $occupiedPositions = Member::whereHas('division', function($q) use ($activePeriod) {
            $q->where('period_id', $activePeriod->id)->where('type', 'dp');
        })->pluck('position_title')->toArray();

        return view('super_admin.members.create_dp', compact('dpPositions', 'occupiedPositions'));
    }

    public function storeDp(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users,nim',
            'angkatan' => 'required|string|max:4',
            'position_name' => 'required|string|max:255',
            'email_prefix' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $fullEmail = strtolower($validated['email_prefix']) . '@himasi.unja.ac.id';
        if (User::where('email', $fullEmail)->exists()) {
            return back()->withErrors(['email_prefix' => 'Email ini sudah terdaftar.'])->withInput();
        }

        DB::transaction(function () use ($validated, $fullEmail, $activePeriod) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $fullEmail,
                'nim' => $validated['nim'],
                'angkatan' => $validated['angkatan'],
                'password' => Hash::make($validated['password']),
                'global_role' => 'dp',
                'avatar' => $this->processAvatarBase64(request()),
            ]);
            
            // Find or create DP division
            $dpDiv = Division::firstOrCreate(
                ['period_id' => $activePeriod->id, 'type' => 'dp'],
                ['name' => 'Dewan Penasehat', 'slug' => 'dp-' . $activePeriod->id, 'description' => 'Otomatis dibuat']
            );
            
            $slug = \Illuminate\Support\Str::slug($validated['position_name']);
            $dpPos = OrgPosition::firstOrCreate(
                ['slug' => $slug],
                ['name' => $validated['position_name'], 'level' => 0]
            );
            
            Member::create([
                'user_id' => $user->id,
                'division_id' => $dpDiv->id,
                'org_position_id' => $dpPos->id,
            ]);
        });
        
        return redirect(route('super_admin.members.index') . '#section-dp')
                         ->with('success', 'Dewan Penasehat berhasil ditambahkan!');
    }

    public function store(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:users,nim',
            'angkatan' => 'required|string|max:4',
            'division_id' => 'required|exists:divisions,id',
            'sub_division_id' => 'nullable|exists:sub_divisions,id',
            'position_name' => 'required|string|max:255',
            'position_name_2' => 'nullable|string|max:255',
            'email_prefix' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        $fullEmail = strtolower($validated['email_prefix']) . '@himasi.unja.ac.id';
        if (User::where('email', $fullEmail)->exists()) {
            return back()->withErrors(['email_prefix' => 'Email ini sudah terdaftar.'])->withInput();
        }

        DB::transaction(function () use ($validated, $fullEmail) {
            $positionTitle = $validated['position_name'];
            if (!empty($validated['position_name_2'])) {
                $positionTitle .= ' & ' . $validated['position_name_2'];
            }
            
            $globalRole = $this->determineGlobalRole($validated['position_name']);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $fullEmail,
                'nim' => $validated['nim'],
                'angkatan' => $validated['angkatan'],
                'password' => Hash::make($validated['password']),
                'global_role' => $globalRole,
                'avatar' => $this->processAvatarBase64(request()),
            ]);
            
            $subDivisionId = $validated['sub_division_id'] ?? null;
            
            if ($subDivisionId) {
                $subDivision = \App\Models\Kepengurusan\SubDivision::find($subDivisionId);
            }

            $slug = \Illuminate\Support\Str::slug($positionTitle);
            $orgPosition = OrgPosition::firstOrCreate(
                ['slug' => $slug],
                ['name' => $positionTitle, 'level' => 99] 
            );
            
            Member::create([
                'user_id' => $user->id,
                'division_id' => $validated['division_id'],
                'sub_division_id' => $subDivisionId,
                'org_position_id' => $orgPosition->id,
                'position_title' => $positionTitle,
            ]);
        });
        
        $hash = '#section-bph';
        $div = \App\Models\Kepengurusan\Division::find($validated['division_id']);
        if ($div) {
            $hash = $div->type === 'divisi' ? '#divisi-' . $div->id : '#section-' . $div->type;
        }

        return redirect(route('super_admin.members.index') . $hash)
                         ->with('success', 'Pengurus berhasil ditambahkan beserta akunnya!');
    }
    
    public function edit($id)
    {
        $member = Member::with('user')->findOrFail($id);
        $activePeriod = Period::where('is_active', true)->first();
        
        if ($member->user->global_role === 'pembina') {
            return view('super_admin.members.edit_pembina', compact('member'));
        } elseif ($member->user->global_role === 'dp') {
            $dpPositions = [
                'Ketua Penasehat',
                'Penasehat Minat dan Bakat',
                'Penasehat Sosial dan Agama',
                'Penasehat Pengembangan Sumber Daya Anggota',
                'Penasehat Pengawasan dan Penyelesaian Masalah',
                'Penasehat Dana dan Usaha',
                'Penasehat Hubungan Masyarakat',
                'Penasehat Media dan Informasi',
                'Penasehat Riset dan Teknologi'
            ];
            return view('super_admin.members.edit_dp', compact('member', 'dpPositions'));
        }
        
        $divisions = Division::where('period_id', $activePeriod->id)->with(['subDivisions', 'members.orgPosition'])->get();
        $positions = OrgPosition::orderBy('level')->get();
        
        return view('super_admin.members.edit', compact('member', 'divisions', 'positions'));
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;
        
        $rules = [
            'name' => 'required|string|max:255',
            'email_prefix' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
        
        if ($user->global_role === 'pembina') {
            $rules['nip'] = 'required|string|max:50|unique:users,nim,' . $user->id;
        } elseif ($user->global_role === 'dp') {
            $rules['nim'] = 'required|string|max:20|unique:users,nim,' . $user->id;
            $rules['angkatan'] = 'required|string|max:4';
            $rules['position_name'] = 'required_without:position_name_multiple|string|max:255|nullable';
            $rules['position_name_multiple'] = 'required_without:position_name|array|nullable';
        } else {
            $rules['nim'] = 'required|string|max:20|unique:users,nim,' . $user->id;
            $rules['angkatan'] = 'required|string|max:4';
            $rules['division_id'] = 'required|exists:divisions,id';
            $rules['position_name'] = 'required|string|max:255';
            $rules['position_name_2'] = 'nullable|string|max:255';
        }
        
        $validated = $request->validate($rules);
        
        $fullEmail = strtolower($validated['email_prefix']) . '@himasi.unja.ac.id';
        if (User::where('email', $fullEmail)->where('id', '!=', $user->id)->exists()) {
            return back()->withErrors(['email_prefix' => 'Email ini sudah digunakan oleh pengguna lain.'])->withInput();
        }
        
        DB::transaction(function () use ($validated, $member, $user, $fullEmail) { $request = request();
            
            $avatarPath = $user->avatar;
            if ($request->has('remove_avatar') && $request->remove_avatar == '1') {
                if ($avatarPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($avatarPath);
                }
                $avatarPath = null;
            } elseif ($request->hasFile('avatar')) {
                if ($avatarPath) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($avatarPath);
                }
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }
            
            $user->update([
                'name' => $validated['name'],
                'email' => $fullEmail,
                'nim' => $user->global_role === 'pembina' ? $validated['nip'] : $validated['nim'],
                'angkatan' => $user->global_role === 'pembina' ? '-' : $validated['angkatan'],
                'avatar' => $avatarPath,
            ]);
            
            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }
            
            $positionTitle = $validated['position_name'];
            if (!empty($validated['position_name_2'])) {
                $positionTitle .= ' & ' . $validated['position_name_2'];
            }
            
            if ($user->global_role === 'dp') {
                $slug = \Illuminate\Support\Str::slug($validated['position_name']);
                $dpPos = OrgPosition::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $validated['position_name'], 'level' => 0]
                );
                $member->update([
                    'org_position_id' => $dpPos->id,
                    'position_title' => $validated['position_name'],
                ]);
            } elseif ($user->global_role !== 'pembina') {
                $slug = \Illuminate\Support\Str::slug($positionTitle);
                $orgPosition = OrgPosition::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $positionTitle, 'level' => 99]
                );
                $member->update([
                    'division_id' => $validated['division_id'],
                    'sub_division_id' => request('sub_division_id'),
                    'org_position_id' => $orgPosition->id,
                    'position_title' => $positionTitle,
                ]);
                
                if (!in_array($user->global_role, ['super_admin'])) {
                    $user->update(['global_role' => $this->determineGlobalRole($validated['position_name'])]);
                }
            } else {
                $member->update([
                    'division_id' => $validated['division_id'],
                    'sub_division_id' => request('sub_division_id'),
                ]);
            }
        });
        
        $hash = '#section-bph';
        $member->refresh();
        if (isset($member) && $member->division) {
            $hash = $member->division->type === 'divisi' ? '#divisi-' . $member->division_id : '#section-' . $member->division->type;
        }

        return redirect(route('super_admin.members.index') . $hash)
                         ->with('success', 'Data pengurus berhasil diperbarui!');
    }

    private function processAvatarBase64($request)
    {
        if ($request->hasFile('avatar')) {
            return $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->filled('avatar_base64')) {
            $base64 = $request->avatar_base64;
            @list($type, $file_data) = explode(';', $base64);
            @list(, $file_data) = explode(',', $file_data);
            
            if ($file_data) {
                $decoded = base64_decode($file_data);
                if ($decoded) {
                    $extension = explode('/', $type)[1] ?? 'png';
                    if ($extension == 'jpeg') $extension = 'jpg';
                    $filename = 'avatars/' . \Illuminate\Support\Str::random(40) . '.' . $extension;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $decoded);
                    return $filename;
                }
            }
        }
        return null;
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;
        
        DB::transaction(function () use ($member, $user) {
            $member->delete();
            
            // Delete user if they have no other member records
            if ($user && $user->memberships()->count() === 0) {
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->delete();
            }
        });

        $hash = '#section-bph';
        if ($member->division) {
            $hash = $member->division->type === 'divisi' ? '#divisi-' . $member->division_id : '#section-' . $member->division->type;
        }

        return redirect(route('super_admin.members.index') . $hash)
                         ->with('success', 'Data anggota berhasil dihapus!');
    }
}
