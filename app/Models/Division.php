<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function relatedTasks()
    {
        return $this->hasMany(Task::class, 'related_division_id');
    }
}
