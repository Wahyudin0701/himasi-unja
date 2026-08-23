<?php

namespace App\Models\Kepanitiaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class EventLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'submitted_by',
        'file_path',
        'status',
        'reviewed_by',
        'review_notes',
        'approved_at',
        'type',
        'letter_number',
        'letter_date',
        'subject',
        'party',
        'notes',
    ];

    protected $casts = [
        'letter_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // ===== Relationships =====

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ===== Scopes =====

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRevision($query)
    {
        return $query->where('status', 'revision');
    }

    // ===== Helpers =====

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRevision(): bool
    {
        return $this->status === 'revision';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu Review',
            'revision' => 'Perlu Revisi',
            'approved' => 'Disetujui',
            default    => 'Tidak Diketahui',
        };
    }
}
