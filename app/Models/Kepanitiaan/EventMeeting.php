<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'date',
        'time',
        'location',
        'description',
        'status',
        'minutes',
        'minutes_file'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventMeetingAttendance::class);
    }
}
