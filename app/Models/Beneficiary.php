<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_report_id', 'name', 'location_name',
        'latitude', 'longitude', 'description',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function report() { return $this->belongsTo(ImpactReport::class, 'impact_report_id'); }
}