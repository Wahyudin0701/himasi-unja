<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kepanitiaan\Event;
use App\Models\User;

class InventoryLoan extends Model
{
    protected $fillable = [
        'inventory_id', 'event_id', 'borrower_id', 'quantity', 
        'status', 'photo_before_path', 'photo_after_path'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
