<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function relatedDivision()
    {
        return $this->belongsTo(Division::class, 'related_division_id');
    }
}
