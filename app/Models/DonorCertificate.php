<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonorCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id', 'certificate_number', 'pdf_path',
        'verification_token', 'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function donation() { return $this->belongsTo(Donation::class); }
}