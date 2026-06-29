<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        'period_id', 'name', 'slug', 'singkatan', 'icon', 
        'color', 'text_color', 'description', 'type', 'base_points'
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function subDivisions()
    {
        return $this->hasMany(SubDivision::class);
    }

    public function workPrograms()
    {
        return $this->hasMany(WorkProgram::class);
    }
}
