<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EventCommittee extends Model
{
    protected $fillable = ['event_id', 'user_id', 'committee_role_id', 'event_division_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(CommitteeRole::class, 'committee_role_id');
    }

    public function division()
    {
        return $this->belongsTo(EventDivision::class, 'event_division_id');
    }
}
