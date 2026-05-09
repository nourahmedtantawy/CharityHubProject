<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImpactPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_report_id', 'path', 'caption', 'sort_order',
    ];

    public function report() { return $this->belongsTo(ImpactReport::class, 'impact_report_id'); }
}