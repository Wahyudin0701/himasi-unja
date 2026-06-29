<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Member extends Model
{
    protected $fillable = ['user_id', 'division_id', 'org_position_id', 'sub_division_id', 'position_title', 'joined_at'];

    protected $casts = [
        'joined_at' => 'date',
    ];

    public function subDivision()
    {
        return $this->belongsTo(SubDivision::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function orgPosition()
    {
        return $this->belongsTo(OrgPosition::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }
}
