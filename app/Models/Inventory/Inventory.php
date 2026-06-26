<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['item_name', 'total_quantity', 'available_quantity'];

    public function loans()
    {
        return $this->hasMany(InventoryLoan::class);
    }
}
