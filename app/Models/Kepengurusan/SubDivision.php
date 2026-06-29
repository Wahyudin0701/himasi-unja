<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;

class SubDivision extends Model
{
    protected $fillable = ['division_id', 'name', 'slug'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
