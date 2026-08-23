<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FinanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'user_id',
        'type',
        'amount',
        'category',
        'description',
        'date',
        'proof_file'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
