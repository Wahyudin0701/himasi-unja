<?php

namespace App\Models\ProgressReport;

use Illuminate\Database\Eloquent\Model;

class DivisionSprint extends Model
{
    protected $fillable = [
        'event_division_id',
        'sprint_number',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function division()
    {
        return $this->belongsTo(\App\Models\Kepanitiaan\EventDivision::class, 'event_division_id');
    }
}
