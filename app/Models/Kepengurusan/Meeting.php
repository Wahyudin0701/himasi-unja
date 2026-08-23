<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'date', 'time', 'location', 'type', 'agenda', 'minutes_text', 'minutes_file'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }
}
