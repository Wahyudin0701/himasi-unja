<?php

namespace App\Http\Controllers\Kepanitiaan\Ketupel;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\Event;
use App\Models\Rab;
use Illuminate\Http\Request;

class RabController extends Controller
{
    private function checkDelegation(Event $event)
    {
        $userRole = auth()->user()->eventCommittees()->where('event_id', $event->id)->first()->role->slug;
        $isKetupel = in_array($userRole, ['ketua-pelaksana', 'wakil-ketua-pelaksana']);
        $hasBenpel = $event->committees()->whereHas('role', function($q) {
            $q->where('slug', 'bendahara-pelaksana');
        })->exists();

        if ($isKetupel && $hasBenpel) {
            abort(403, 'Akses ditolak. Pengelolaan RAB telah didelegasikan sepenuhnya kepada Bendahara Pelaksana.');
        }
    }

    public function index(Event $event)
    {
        $this->checkDelegation($event);
        
        // Get all rabs for this event, grouped by division
        $rabs = Rab::with('division')
            ->where('event_id', $event->id)
            ->get()
            ->groupBy('event_division_id');
            
        // Get assignment for layout
        $assignment = auth()->user()->eventCommittees()
            ->where('event_id', $event->id)
            ->first();

        // Calculate grand total
        $grandTotal = $rabs->flatten()->sum('total_price');

        return view('kepanitiaan.ketupel.rab.index', compact('event', 'rabs', 'assignment', 'grandTotal'));
    }

    public function print(Event $event)
    {
        $this->checkDelegation($event);
        
        // Get all rabs for this event, grouped by division
        $rabs = Rab::with('division')
            ->where('event_id', $event->id)
            ->get()
            ->groupBy('event_division_id');
            
        // Calculate grand total
        $grandTotal = $rabs->flatten()->sum('total_price');

        return view('kepanitiaan.ketupel.rab.print', compact('event', 'rabs', 'grandTotal'));
    }
}
