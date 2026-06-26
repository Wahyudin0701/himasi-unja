<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;

class OrgPosition extends Model
{
    protected $fillable = ['name', 'slug', 'level'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
