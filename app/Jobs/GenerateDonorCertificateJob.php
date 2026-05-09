<?php
namespace App\Jobs;

use App\Models\Donation;
use App\Models\DonorCertificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateDonorCertificateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Donation $donation) {}

    public function handle(): void
    {
        $certificateNumber  = 'CH-' . strtoupper(Str::random(8));
        $verificationToken  = Str::uuid()->toString();

        $certificate = DonorCertificate::create([
            'donation_id'        => $this->donation->id,
            'certificate_number' => $certificateNumber,
            'verification_token' => $verificationToken,
            'issued_at'          => now(),
        ]);

        $pdf = Pdf::loadView('certificates.donor', [
            'donation'    => $this->donation,
            'certificate' => $certificate,
            'verifyUrl'   => route('certificates.verify', $verificationToken),
        ])->setPaper('a4', 'landscape');

        $path = "certificates/{$certificateNumber}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        $certificate->update(['pdf_path' => $path]);
    }
}