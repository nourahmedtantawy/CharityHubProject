<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VolunteerHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'volunteer_id', 'volunteer_shift_id', 'hours',
        'date', 'notes', 'status', 'approved_by',
    ];

    protected $casts = [
        'hours' => 'decimal:2',
        'date'  => 'date',
    ];

    public function volunteer()   { return $this->belongsTo(Volunteer::class); }
    public function shift()       { return $this->belongsTo(VolunteerShift::class, 'volunteer_shift_id'); }
    public function approvedBy()  { return $this->belongsTo(User::class, 'approved_by'); }
}