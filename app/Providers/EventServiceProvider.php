<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\DonationReceived;
use App\Listeners\SendDonationThankYouEmail;
use App\Listeners\GenerateDonorCertificate;
use App\Listeners\UpdateCampaignRaisedAmount;
use App\Listeners\LogDonationTransaction;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DonationReceived::class => [
            UpdateCampaignRaisedAmount::class,
            LogDonationTransaction::class,
            GenerateDonorCertificate::class,
            SendDonationThankYouEmail::class,
        ],
    ];
}