<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function rundowns()
    {
        return $this->hasMany(Rundown::class, 'pic_id');
    }

    public function verifiedLetters()
    {
        return $this->hasMany(Letter::class, 'verified_by');
    }

    public function uploadedLetters()
    {
        return $this->hasMany(Letter::class, 'uploaded_by');
    }

    public function inventoryLoans()
    {
        return $this->hasMany(InventoryLoan::class, 'borrower_id');
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class, 'pic_id');
    }

    public function uploadedAssets()
    {
        return $this->hasMany(DesignAsset::class, 'uploaded_by');
    }

    public function violationsCommitted()
    {
        return $this->hasMany(Violation::class, 'user_id');
    }

    public function violationsLogged()
    {
        return $this->hasMany(Violation::class, 'logged_by');
    }
}
