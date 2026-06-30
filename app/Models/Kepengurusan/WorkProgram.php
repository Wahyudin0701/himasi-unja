<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepanitiaan\Event;
use App\Models\ProgressReport\WorkTask;

class WorkProgram extends Model
{
    protected $fillable = [
        'division_id', 'sub_division_id', 'pic_id', 'name', 'type', 'description', 
        'objective', 'target_audience', 'start_date', 'end_date', 'budget_plan', 'status', 'cancellation_reason'
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

    public function pic()
    {
        return $this->belongsTo(\App\Models\User::class, 'pic_id');
    }

    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class);
    }

    public function partnerDivisions()
    {
        return $this->belongsToMany(Division::class, 'work_program_partner_divisions', 'work_program_id', 'division_id')
                    ->withTimestamps();
    }

    public function collaborators()
    {
        return $this->belongsToMany(\App\Models\User::class, 'work_program_collaborators', 'work_program_id', 'user_id')
                    ->withTimestamps();
    }

    public function getStatusAttribute($value)
    {
        if ($value === 'cancelled') {
            return 'cancelled';
        }

        if (!$this->start_date || !$this->end_date) {
            return $value;
        }

        $now = now()->startOfDay();
        $start = $this->start_date->copy()->startOfDay();
        $end = $this->end_date->copy()->endOfDay();

        if ($now->lt($start)) {
            return 'planning';
        } elseif ($now->between($start, $end)) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function tasks()
    {
        return $this->hasMany(\App\Models\ProgressReport\WorkTask::class);
    }

    public function logs()
    {
        return $this->hasMany(ProkerLog::class);
    }
}
