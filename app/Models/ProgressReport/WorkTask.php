<?php

namespace App\Models\ProgressReport;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\Kepengurusan\Division;
use App\Models\Kepanitiaan\Event;
use App\Models\Kepanitiaan\EventDivision;
use App\Models\User;

class WorkTask extends Model
{
    protected $fillable = [
        'work_program_id', 'event_id', 'division_id', 'event_division_id',
        'title', 'description', 'attachments', 'assigned_to', 'assigned_by',
        'sprint_number', 'sprint_start_date', 'sprint_end_date',
        'due_date', 'priority', 'status', 'completed_at', 'revision_note'
    ];

    protected $casts = [
        'sprint_start_date' => 'date',
        'sprint_end_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function workProgram()
    {
        return $this->belongsTo(WorkProgram::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function eventDivision()
    {
        return $this->belongsTo(EventDivision::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function reports()
    {
        return $this->hasMany(ProgressReport::class);
    }
}
