<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class MedicalInventory extends Model
{
    protected $fillable = ['medicine_name', 'quantity', 'expiration_date'];

    protected $casts = [
        'expiration_date' => 'date',
    ];
}
