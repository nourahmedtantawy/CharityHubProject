<x-filament-panels::page>

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'Total Raised',      'value' => 'EGP ' . number_format($totalRaised),  'icon' => '💰', 'color' => 'green'],
            ['label' => 'Completed Donations','value' => number_format($totalDonations),         'icon' => '✅', 'color' => 'blue'],
            ['label' => 'Pending Donations',  'value' => $pendingDonations,                      'icon' => '⏳', 'color' => 'amber'],
            ['label' => 'Active Campaigns',   'value' => $activeCampaigns,                       'icon' => '📋', 'color' => 'violet'],
            ['label' => 'Total Campaigns',    'value' => $totalCampaigns,                        'icon' => '🗂️', 'color' => 'indigo'],
            ['label' => 'Total Users',        'value' => $totalUsers,                            'icon' => '👥', 'color' => 'sky'],
            ['label' => 'Volunteers',         'value' => $totalVolunteers,                       'icon' => '🤝', 'color' => 'teal'],
        ] as $stat)
        <div class="fi-card rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 p-5 shadow-sm">
            <div class="text-2xl mb-2">{{ $stat['icon'] }}</div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent donations --}}
        <div class="fi-card rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Recent Donations</h3>
                <a href="{{ route('filament.admin.resources.donations.index') }}"
                   class="text-xs text-primary-600 hover:underline">View all →</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                @forelse($recentDonations as $donation)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-sm">
                            {{ strtoupper(substr($donation->donor_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $donation->donor_name }}</p>
                            <p class="text-xs text-gray-400">{{ Str::limit($donation->campaign->title, 30) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">{{ number_format($donation->amount) }} EGP</p>
                        <p class="text-xs text-gray-400">{{ $donation->donated_at?->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="px-5 py-4 text-sm text-gray-400">No donations yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Top campaigns --}}
        <div class="fi-card rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Top Campaigns</h3>
                <a href="{{ route('filament.admin.resources.campaigns.index') }}"
                   class="text-xs text-primary-600 hover:underline">View all →</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                @forelse($topCampaigns as $campaign)
                <div class="px-5 py-3">
                    <div class="flex justify-between items-center mb-1.5">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ Str::limit($campaign->title, 35) }}
                        </p>
                        <span class="text-xs font-bold text-primary-600">
                            {{ $campaign->progress_percentage }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-white/10 rounded-full h-1.5">
                        <div class="bg-primary-500 h-1.5 rounded-full"
                             style="width: {{ min(100, $campaign->progress_percentage) }}%">
                        </div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-400">
                            {{ number_format($campaign->raised_amount) }} EGP raised
                        </p>
                        <p class="text-xs text-gray-400">
                            Goal: {{ number_format($campaign->goal_amount) }} EGP
                        </p>
                    </div>
                </div>
                @empty
                <p class="px-5 py-4 text-sm text-gray-400">No active campaigns.</p>
                @endforelse
            </div>
        </div>

    </div>

</x-filament-panels::page>