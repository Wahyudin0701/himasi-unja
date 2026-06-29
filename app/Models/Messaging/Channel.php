<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kepengurusan\Division;
use App\Models\Kepengurusan\Period;

class Channel extends Model
{
    protected $fillable = ['name', 'type', 'division_id', 'period_id'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'channel_members')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function unreadCountFor($userId)
    {
        return $this->messages()
            ->whereDoesntHave('reads', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('sender_id', '!=', $userId)
            ->count();
    }
}
