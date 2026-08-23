<?php

namespace App\Models\Kepengurusan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'letter_number', 'letter_date', 'subject', 'party', 'file_path'
    ];

    protected $casts = [
        'letter_date' => 'date',
    ];
}
