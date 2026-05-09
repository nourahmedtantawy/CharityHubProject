<?php
namespace App\Listeners;

use App\Events\DonationReceived;
use App\Mail\DonationThankYouMail;
use Illuminate\Support\Facades\Mail;

class SendDonationThankYouEmail
{
    public function handle(DonationReceived $event): void
    {
        Mail::to($event->donation->donor_email)
            ->queue(new DonationThankYouMail($event->donation));
    }
}