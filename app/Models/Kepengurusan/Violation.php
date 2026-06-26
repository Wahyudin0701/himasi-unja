<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Violation extends Model
{
    protected $fillable = ['member_id', 'description', 'point_deduction', 'logged_by'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function logger()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
