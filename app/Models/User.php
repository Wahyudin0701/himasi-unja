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
        'nim',
        'angkatan',
        'password',
        'avatar',
        'dietary_restrictions',
        'global_role',
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

    // A user can be a collaborator in cross-division collaborative work programs
    public function collaboratingWorkPrograms()
    {
        return $this->belongsToMany(\App\Models\Kepengurusan\WorkProgram::class, 'work_program_collaborators', 'user_id', 'work_program_id')
                    ->withTimestamps();
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

    // ===== KEPANITIAAN HELPERS =====

    /**
     * Cek apakah user adalah Ketua Pelaksana di suatu event
     */
    public function isKetupelInEvent($eventId): bool
    {
        return $this->eventCommittees()
            ->where('event_id', $eventId)
            ->whereHas('role', fn($q) => $q->where('slug', 'ketua-pelaksana'))
            ->exists();
    }

    /**
     * Cek apakah user adalah Koordinator Divisi (CO) di suatu event
     */
    public function isCOInEvent($eventId): bool
    {
        return $this->eventCommittees()
            ->where('event_id', $eventId)
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->exists();
    }

    /**
     * Cek apakah user adalah Anggota panitia di suatu event
     */
    public function isAnggotaInEvent($eventId): bool
    {
        return $this->eventCommittees()
            ->where('event_id', $eventId)
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->exists();
    }

    /**
     * Ambil committee role user di event tertentu (beserta relasi)
     */
    public function getCommitteeRoleInEvent($eventId)
    {
        return $this->eventCommittees()
            ->where('event_id', $eventId)
            ->with(['role', 'division'])
            ->first();
    }

    /**
     * Ambil semua event aktif dimana user terlibat sebagai panitia
     */
    public function getActiveEvents()
    {
        return $this->eventCommittees()
            ->with(['event', 'role', 'division'])
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->get();
    }

    /**
     * Cek apakah user adalah Ketua Pelaksana (atau Wakil Ketupel) di event manapun yang aktif.
     * Slug: ketua-pelaksana, wakil-ketupel
     */
    public function hasActiveKetupelRole(): bool
    {
        return $this->eventCommittees()
            ->whereHas('role', fn($q) => $q->whereIn('slug', ['ketua-pelaksana', 'wakil-ketua-pelaksana']))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->exists();
    }

    /**
     * Cek apakah user adalah Sekretaris atau Bendahara Pelaksana di event manapun yang aktif.
     * Slug: sekretaris-pelaksana, bendahara-pelaksana
     */
    public function hasActiveSekpelBenpelRole(): bool
    {
        return $this->eventCommittees()
            ->whereHas('role', fn($q) => $q->whereIn('slug', ['sekretaris-pelaksana', 'bendahara-pelaksana']))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->exists();
    }

    /**
     * @deprecated Gunakan hasActiveKetupelRole() atau hasActiveSekpelBenpelRole()
     */
    public function hasIntiCommitteeRole(): bool
    {
        return $this->hasActiveKetupelRole();
    }

    /**
     * Cek apakah user memiliki role CO di event manapun yang aktif
     */
    public function hasActiveCORole(): bool
    {
        return $this->eventCommittees()
            ->whereHas('role', fn($q) => $q->where('slug', 'co-divisi'))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->exists();
    }

    /**
     * Cek apakah user memiliki role anggota di event manapun yang aktif
     */
    public function hasActiveAnggotaRole(): bool
    {
        return $this->eventCommittees()
            ->whereHas('role', fn($q) => $q->where('slug', 'anggota'))
            ->whereHas('event', fn($q) => $q->whereIn('status', ['planning', 'preparation', 'ongoing']))
            ->exists();
    }
}
