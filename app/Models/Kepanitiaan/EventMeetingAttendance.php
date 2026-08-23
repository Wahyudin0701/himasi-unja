<?php

namespace App\Models\Kepanitiaan;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMeetingAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_meeting_id',
        'user_id',
        'status',
        'reason'
    ];

    public function meeting()
    {
        return $this->belongsTo(EventMeeting::class, 'event_meeting_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
