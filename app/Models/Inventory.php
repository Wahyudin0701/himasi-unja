<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function loans()
    {
        return $this->hasMany(InventoryLoan::class);
    }
}
