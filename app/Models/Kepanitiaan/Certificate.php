<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'design_asset_id', 'guest_id', 'stamp_file_path', 'generated_file_path'
    ];

    public function designAsset()
    {
        return $this->belongsTo(DesignAsset::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
