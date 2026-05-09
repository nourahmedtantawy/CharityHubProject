<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerShift extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'title', 'description', 'location',
        'starts_at', 'ends_at', 'max_volunteers', 'registered_count',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function campaign()      { return $this->belongsTo(Campaign::class); }
    public function registrations() { return $this->hasMany(ShiftRegistration::class); }
    public function volunteers()    { return $this->belongsToMany(Volunteer::class, 'shift_registrations'); }

    public function hasConflictFor(Volunteer $volunteer): bool
    {
        return ShiftRegistration::where('volunteer_id', $volunteer->id)
            ->whereHas('shift', function ($q) {
                $q->where('starts_at', '<', $this->ends_at)
                  ->where('ends_at', '>', $this->starts_at);
            })->exists();
    }

    public function isFull(): bool
    {
        return $this->registered_count >= $this->max_volunteers;
    }
}