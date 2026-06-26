<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepengurusan\Period;
use App\Models\Kepengurusan\WorkProgram;
use App\Models\User;
use App\Models\ProgressReport\WorkTask;
use App\Models\Inventory\InventoryLoan;

class Event extends Model
{
    protected $fillable = [
        'period_id', 'work_program_id', 'created_by', 'name', 
        'description', 'event_date', 'end_date', 'location', 
        'status', 'budget_total'
    ];

    protected $casts = [
        'event_date' => 'date',
        'end_date' => 'date',
        'budget_total' => 'decimal:2',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function workProgram()
    {
        return $this->belongsTo(WorkProgram::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function divisions()
    {
        return $this->hasMany(EventDivision::class);
    }

    public function committees()
    {
        return $this->hasMany(EventCommittee::class);
    }

    public function tasks()
    {
        return $this->hasMany(WorkTask::class);
    }

    public function rundowns()
    {
        return $this->hasMany(Rundown::class);
    }

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function designAssets()
    {
        return $this->hasMany(DesignAsset::class);
    }

    public function inventoryLoans()
    {
        return $this->hasMany(InventoryLoan::class);
    }
}
