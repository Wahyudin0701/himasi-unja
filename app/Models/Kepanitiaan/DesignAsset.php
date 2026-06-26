<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DesignAsset extends Model
{
    protected $fillable = [
        'event_id', 'asset_name', 'asset_type', 'file_path', 'uploaded_by'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
