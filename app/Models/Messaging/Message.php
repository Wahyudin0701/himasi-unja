<?php

namespace App\Models\Messaging;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    protected $fillable = ['channel_id', 'sender_id', 'body', 'attachment_path', 'attachment_name'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function isReadBy($userId)
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }
}
