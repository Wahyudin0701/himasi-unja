<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProgressReport\WorkTask;

class EventDivision extends Model
{
    protected $fillable = ['event_id', 'name', 'slug', 'sort_order'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function committees()
    {
        return $this->hasMany(EventCommittee::class);
    }

    public function tasks()
    {
        return $this->hasMany(WorkTask::class);
    }
}
