<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'campaign_id', 'amount', 'currency',
        'frequency', 'gateway_subscription_id', 'status',
        'next_billing_date', 'cancelled_at',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'next_billing_date' => 'datetime',
        'cancelled_at'      => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function campaign() { return $this->belongsTo(Campaign::class); }
}