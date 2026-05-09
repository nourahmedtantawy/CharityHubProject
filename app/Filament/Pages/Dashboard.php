<?php
namespace App\Filament\Pages;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\Volunteer;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string  $view           = 'filament.pages.dashboard';
    protected static ?string $title          = 'Dashboard';
    protected static ?int    $navigationSort = -1;

    public function getViewData(): array
    {
        return [
            'totalRaised'      => Donation::where('status', 'completed')->sum('amount'),
            'totalDonations'   => Donation::where('status', 'completed')->count(),
            'pendingDonations' => Donation::where('status', 'pending')->count(),
            'activeCampaigns'  => Campaign::where('status', 'active')->count(),
            'totalCampaigns'   => Campaign::count(),
            'totalUsers'       => User::count(),
            'totalVolunteers'  => Volunteer::where('status', 'approved')->count(),
            'recentDonations'  => Donation::with('campaign')
                ->where('status', 'completed')
                ->latest('donated_at')
                ->take(8)
                ->get(),
            'topCampaigns'     => Campaign::where('status', 'active')
                ->orderBy('raised_amount', 'desc')
                ->take(5)
                ->get(),
        ];
    }
}