<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rundown extends Model
{
    protected $fillable = [
        'event_id', 'activity_name', 'start_time', 'end_time', 
        'status', 'notes', 'pic_id'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
}
