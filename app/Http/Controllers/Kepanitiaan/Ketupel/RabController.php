<?php

namespace App\Http\Controllers\Kepanitiaan\Ketupel;

use App\Http\Controllers\Controller;
use App\Models\Kepanitiaan\Event;
use App\Models\Rab;
use Illuminate\Http\Request;

class RabController extends Controller
{
    public function index(Event $event)
    {
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
}
