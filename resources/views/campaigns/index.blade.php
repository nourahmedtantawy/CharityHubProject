@extends('layouts.app')
@section('title', 'All Campaigns')

@section('content')

{{-- Hero --}}
<div class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 rounded-3xl overflow-hidden mb-10 px-8 py-14 text-white shadow-xl">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full translate-y-1/2 -translate-x-1/2"></div>
    </div>
    <div class="relative z-10 max-w-2xl">
        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm font-medium mb-4">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            {{ \App\Models\Campaign::where('status','active')->count() }} active campaigns
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-4">
            Make a Real<br>Difference Today
        </h1>
        <p class="text-blue-100 text-lg mb-6">
            Every donation is tracked transparently. See exactly how your money creates impact.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="#campaigns"
               class="bg-white text-blue-700 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50 transition-colors shadow-md">
                Browse Campaigns
            </a>
            @guest
            <a href="{{ route('register') }}"
               class="border border-white/40 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/10 transition-colors">
                Create Account
            </a>
            @endguest
        </div>
    </div>
</div>

{{-- Stats bar --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
    @php
        $totalRaised   = \App\Models\Campaign::sum('raised_amount');
        $totalDonors   = \App\Models\Donation::where('status','completed')->distinct('donor_email')->count();
        $totalCampaigns= \App\Models\Campaign::where('status','active')->count();
        $totalVolunteers= \App\Models\Volunteer::where('status','approved')->count();
    @endphp
    @foreach([
        ['label' => 'Total Raised',   'value' => 'EGP ' . number_format($totalRaised),   'icon' => '💰', 'color' => 'blue'],
        ['label' => 'Donors',         'value' => number_format($totalDonors),             'icon' => '👥', 'color' => 'indigo'],
        ['label' => 'Active Campaigns','value' => $totalCampaigns,                        'icon' => '📋', 'color' => 'violet'],
        ['label' => 'Volunteers',     'value' => $totalVolunteers,                        'icon' => '🤝', 'color' => 'sky'],
    ] as $stat)
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="text-2xl mb-1">{{ $stat['icon'] }}</div>
        <p class="text-2xl font-bold text-slate-800">{{ $stat['value'] }}</p>
        <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Search & filter --}}
<div id="campaigns" class="bg-white rounded-2xl border border-slate-200 p-5 mb-6 shadow-sm">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text" name="search"
                value="{{ request('search') }}"
                placeholder="Search campaigns..."
                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
        </div>
        <select name="category"
                class="border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            <option value="">All Categories</option>
            @foreach(['health','education','orphans','shelter','food','environment','disaster','other'] as $cat)
                <option value="{{ $cat }}" @selected(request('category') === $cat)>
                    {{ ucfirst($cat) }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
            Search
        </button>
        @if(request('search') || request('category'))
            <a href="{{ route('campaigns.index') }}"
               class="border border-slate-300 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                Clear
            </a>
        @endif
    </form>
</div>

{{-- Category pills --}}
<div class="flex flex-wrap gap-2 mb-6">
    @php
        $categoryIcons = [
            'health' => '🏥', 'education' => '📚', 'orphans' => '👶',
            'shelter' => '🏠', 'food' => '🍞', 'environment' => '🌿',
            'disaster' => '🆘', 'other' => '💙',
        ];
    @endphp
    <a href="{{ route('campaigns.index') }}"
       class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:border-blue-400 hover:text-blue-600' }}">
        All
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('campaigns.index') }}?category={{ $cat }}"
           class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors {{ request('category') === $cat ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:border-blue-400 hover:text-blue-600' }}">
            {{ $categoryIcons[$cat] ?? '💙' }} {{ ucfirst($cat) }}
        </a>
    @endforeach
</div>

{{-- Campaigns grid --}}
@if($campaigns->isEmpty())
    <div class="text-center py-20 bg-white rounded-2xl border border-slate-200">
        <div class="text-5xl mb-4">🔍</div>
        <p class="text-xl font-semibold text-slate-700 mb-2">No campaigns found</p>
        <p class="text-slate-400 mb-6">Try adjusting your search or filter.</p>
        <a href="{{ route('campaigns.index') }}" class="text-blue-600 font-medium hover:underline">Clear filters</a>
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($campaigns as $campaign)
            @include('components.campaign-card', ['campaign' => $campaign])
        @endforeach
    </div>
    <div class="mt-8">{{ $campaigns->withQueryString()->links() }}</div>
@endif

@endsection