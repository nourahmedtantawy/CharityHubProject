<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id', 'volunteer_shift_id', 'status',
    ];

    public function volunteer() { return $this->belongsTo(Volunteer::class); }
    public function shift()     { return $this->belongsTo(VolunteerShift::class, 'volunteer_shift_id'); }
}