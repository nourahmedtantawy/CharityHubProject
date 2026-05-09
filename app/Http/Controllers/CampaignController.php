<?php
namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    // Show all active campaigns (public listing page)
    public function index(Request $request)
    {
        $campaigns = Campaign::published()
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->when($request->search,   fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->withCount('donations')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = Campaign::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    // Show a single campaign by its SEO slug
    public function show(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->where('status', 'active')
            ->withCount('donations')
            ->firstOrFail();

        // Recent donations feed (non-anonymous only)
        $recentDonations = $campaign->donations()
            ->where('status', 'completed')
            ->where('is_anonymous', false)
            ->latest('donated_at')
            ->take(10)
            ->get();

        return view('campaigns.show', compact('campaign', 'recentDonations'));
    }
}