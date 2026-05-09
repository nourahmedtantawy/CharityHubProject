<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImpactReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id', 'title', 'summary', 'content',
        'beneficiaries_count', 'report_date', 'is_published',
    ];

    protected $casts = [
        'report_date'  => 'date',
        'is_published' => 'boolean',
    ];

    public function campaign()      { return $this->belongsTo(Campaign::class); }
    public function beneficiaries() { return $this->hasMany(Beneficiary::class); }
    public function photos()        { return $this->hasMany(ImpactPhoto::class)->orderBy('sort_order'); }
}