<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Kepengurusan\Member;
use App\Models\Kepanitiaan\EventCommittee;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'dietary_restrictions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // A user can be a member of the organization (pengurus)
    public function memberships()
    {
        return $this->hasMany(Member::class);
    }

    // A user can be a committee member in events (panitia / volunteer)
    public function eventCommittees()
    {
        return $this->hasMany(EventCommittee::class);
    }

    // Helper: Cek apakah user adalah pengurus di periode tertentu
    public function isMemberInPeriod($periodId)
    {
        return $this->memberships()->whereHas('division', function($query) use ($periodId) {
            $query->where('period_id', $periodId);
        })->exists();
    }
}
