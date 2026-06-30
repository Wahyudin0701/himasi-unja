<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ProkerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_program_id',
        'author_id',
        'content',
        'progress_update',
        'attachment',
        'status',
        'feedback',
    ];

    public function workProgram()
    {
        return $this->belongsTo(WorkProgram::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
