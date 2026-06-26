<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepanitiaan\Event;

class Period extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_active', 'archived_at'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
