<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    protected $fillable = [
        'event_id',
        'event_division_id',
        'item_name',
        'quantity',
        'unit',
        'unit_price',
        'total_price'
    ];

    public function event()
    {
        return $this->belongsTo(Kepanitiaan\Event::class);
    }

    public function division()
    {
        return $this->belongsTo(Kepanitiaan\EventDivision::class, 'event_division_id');
    }
}
