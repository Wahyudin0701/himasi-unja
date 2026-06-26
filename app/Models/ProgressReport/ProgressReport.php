<?php

namespace App\Models\ProgressReport;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ProgressReport extends Model
{
    protected $fillable = [
        'work_task_id', 'user_id', 'content', 'attachments', 
        'status_update', 'reviewed_by', 'reviewed_at', 'review_notes'
    ];

    protected $casts = [
        'attachments' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(WorkTask::class, 'work_task_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
