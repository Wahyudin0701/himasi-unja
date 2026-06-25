<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
