<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function designAsset()
    {
        return $this->belongsTo(DesignAsset::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
