<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Filament: only admins can access the panel ────────
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    // ── Role helpers ──────────────────────────────────────
    public function isAdmin(): bool     { return $this->role === 'admin'; }
    public function isDonor(): bool     { return $this->role === 'donor'; }
    public function isVolunteer(): bool { return $this->role === 'volunteer'; }

    // ── Relationships ─────────────────────────────────────
    public function volunteer()     { return $this->hasOne(\App\Models\Volunteer::class); }
    public function donations()     { return $this->hasMany(\App\Models\Donation::class); }
    public function subscriptions() { return $this->hasMany(\App\Models\DonationSubscription::class); }
    public function campaigns()     { return $this->hasMany(\App\Models\Campaign::class, 'created_by'); }
}