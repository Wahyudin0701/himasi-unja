<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Sponsor extends Model
{
    protected $fillable = [
        'event_id', 'company_name', 'contact_person', 'status', 'pic_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }
}
