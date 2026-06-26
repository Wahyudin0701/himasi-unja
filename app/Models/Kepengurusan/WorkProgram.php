<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepanitiaan\Event;
use App\Models\ProgressReport\WorkTask;

class WorkProgram extends Model
{
    protected $fillable = [
        'division_id', 'name', 'type', 'description', 
        'start_date', 'end_date', 'budget_plan', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget_plan' => 'decimal:2',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function tasks()
    {
        return $this->hasMany(WorkTask::class);
    }
}
