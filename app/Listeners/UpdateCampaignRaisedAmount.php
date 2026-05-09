<?php
namespace App\Listeners;

use App\Events\DonationReceived;

class UpdateCampaignRaisedAmount
{
    public function handle(DonationReceived $event): void
    {
        $donation = $event->donation;
        $campaign = $donation->campaign;
        $campaign->increment('raised_amount', $donation->amount);

        if ($campaign->raised_amount >= $campaign->goal_amount) {
            $campaign->update(['status' => 'completed']);
        }
    }
}