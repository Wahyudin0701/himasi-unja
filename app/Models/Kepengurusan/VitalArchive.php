<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VitalArchive extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'date', 'file_path', 'uploaded_by'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }
}
