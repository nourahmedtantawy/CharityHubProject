<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id', 'gateway', 'event_type', 'gateway_event_id',
        'amount', 'currency', 'status', 'payload', 'ip_address',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'payload' => 'array',
    ];

    public function donation() { return $this->belongsTo(Donation::class); }
}