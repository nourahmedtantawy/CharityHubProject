<?php
namespace App\Listeners;

use App\Events\DonationReceived;
use App\Jobs\GenerateDonorCertificateJob;

class GenerateDonorCertificate
{
    public function handle(DonationReceived $event): void
    {
        GenerateDonorCertificateJob::dispatch($event->donation)->delay(now()->addSeconds(5));
    }
}