<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'phone', 'address', 'date_of_birth',
        'skills', 'bio', 'status', 'total_hours',
    ];

    protected $casts = [
        'skills'        => 'array',
        'date_of_birth' => 'date',
        'total_hours'   => 'decimal:2',
    ];

    public function user()              { return $this->belongsTo(User::class); }
    public function shiftRegistrations(){ return $this->hasMany(ShiftRegistration::class); }
    public function hours()             { return $this->hasMany(VolunteerHour::class); }
    public function shifts()            { return $this->belongsToMany(VolunteerShift::class, 'shift_registrations'); }
}