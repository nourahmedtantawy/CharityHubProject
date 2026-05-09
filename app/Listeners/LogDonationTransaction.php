<?php
namespace App\Listeners;

use App\Events\DonationReceived;
use App\Models\TransactionLog;

class LogDonationTransaction
{
    public function handle(DonationReceived $event): void
    {
        TransactionLog::create([
            'donation_id' => $event->donation->id,
            'gateway'     => $event->donation->gateway,
            'event_type'  => 'donation.completed',
            'amount'      => $event->donation->amount,
            'currency'    => $event->donation->currency,
            'status'      => 'success',
            'payload'     => $event->donation->toArray(),
        ]);
    }
}