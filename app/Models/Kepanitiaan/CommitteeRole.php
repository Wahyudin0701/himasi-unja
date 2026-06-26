<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;

class CommitteeRole extends Model
{
    protected $fillable = ['name', 'slug', 'level', 'scope'];

    public function committees()
    {
        return $this->hasMany(EventCommittee::class);
    }
}
