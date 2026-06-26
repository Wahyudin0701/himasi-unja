<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Letter extends Model
{
    protected $fillable = [
        'event_id', 'title', 'type', 'draft_file_path', 
        'letter_number', 'verified_by', 'scan_file_path', 
        'uploaded_by', 'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
