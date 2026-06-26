<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'event_id', 'name', 'type', 'attendance_status', 'dietary_restrictions'
    ];

    protected $casts = [
        'attendance_status' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
