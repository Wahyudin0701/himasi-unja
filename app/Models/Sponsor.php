<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
}
