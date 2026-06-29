<?php

namespace App\Http\Controllers\Kepengurusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\Period;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $activePeriod = Period::where('is_active', true)->first();
        
        if (!$activePeriod) {
            return view('kepengurusan.directory.index', [
                'activePeriod' => null,
                'bph' => null,
                'divisions' => collect(),
                'filteredDivisions' => collect(),
                'dp' => null,
                'pembina' => null,
            ]);
        }
        
        $pembina = Division::where('period_id', $activePeriod->id)
            ->where('type', 'pembina')
            ->with(['members' => function($q) {
                $q->with(['user', 'orgPosition']);
            }])
            ->first();

        $dp = Division::where('period_id', $activePeriod->id)
            ->where('type', 'dp')
            ->with(['members' => function($q) {
                $q->with(['user', 'orgPosition']);
            }])
            ->first();

        $bph = Division::where('period_id', $activePeriod->id)
            ->where('type', 'bph')
            ->with(['members' => function($q) {
                $q->with(['user', 'orgPosition']);
            }])
            ->first();

        $divisions = Division::where('period_id', $activePeriod->id)
            ->where('type', 'divisi')
            ->get();
            
        // For the staff section
        $divisiQuery = Division::where('period_id', $activePeriod->id)
            ->where('type', 'divisi')
            ->with(['members' => function($q) {
                $q->with(['user', 'orgPosition', 'subDivision']);
            }]);
            
        if ($request->has('division_id') && $request->division_id != '') {
            $divisiQuery->where('id', $request->division_id);
        }
        
        $filteredDivisions = $divisiQuery->get();

        return view('kepengurusan.directory.index', compact('activePeriod', 'pembina', 'dp', 'bph', 'divisions', 'filteredDivisions'));
    }
}
