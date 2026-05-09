<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'campaign_id', 'user_id', 'donor_name', 'donor_email', 'donor_phone',
        'amount', 'currency', 'type', 'status', 'gateway',
        'gateway_transaction_id', 'idempotency_key', 'is_anonymous', 'message', 'donated_at',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'is_anonymous' => 'boolean',
        'donated_at'   => 'datetime',
    ];

    public function campaign()    { return $this->belongsTo(Campaign::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function certificate() { return $this->hasOne(DonorCertificate::class); }
    public function logs()        { return $this->hasMany(TransactionLog::class); }
}