<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCommitteeRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // Get Event from Route
        $event = $request->route('event');
        $eventId = is_object($event) ? $event->id : $event;

        if (!$eventId) {
            abort(403, 'Event not found in route.');
        }

        // Base query for committees related to the current event
        $query = $user->eventCommittees()->where('event_id', $eventId)->with('role');

        // Check if division context is required (for CO and Anggota)
        $division = $request->route('division');
        $divisionId = is_object($division) ? $division->id : $division;

        if ($divisionId && (in_array('co-divisi', $allowedRoles) || in_array('anggota', $allowedRoles))) {
            $query->where('event_division_id', $divisionId);
        }

        $userCommittees = $query->get();

        // Check if any of the user's roles match the allowed roles
        foreach ($userCommittees as $committee) {
            if ($committee->role && in_array($committee->role->slug, $allowedRoles)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
