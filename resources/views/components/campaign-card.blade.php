@php
    $categoryColors = [
        'health'      => 'bg-red-50 text-red-700',
        'education'   => 'bg-blue-50 text-blue-700',
        'orphans'     => 'bg-purple-50 text-purple-700',
        'shelter'     => 'bg-amber-50 text-amber-700',
        'food'        => 'bg-orange-50 text-orange-700',
        'environment' => 'bg-green-50 text-green-700',
        'disaster'    => 'bg-rose-50 text-rose-700',
        'other'       => 'bg-slate-50 text-slate-700',
    ];
    $categoryIcons = [
        'health' => '🏥', 'education' => '📚', 'orphans' => '👶',
        'shelter' => '🏠', 'food' => '🍞', 'environment' => '🌿',
        'disaster' => '🆘', 'other' => '💙',
    ];
    $colorClass = $categoryColors[$campaign->category] ?? 'bg-blue-50 text-blue-700';
    $icon = $categoryIcons[$campaign->category] ?? '💙';

    $progressColor = match(true) {
        $campaign->progress_percentage >= 80 => 'bg-green-500',
        $campaign->progress_percentage >= 50 => 'bg-blue-500',
        default                              => 'bg-amber-500',
    };
@endphp

<a href="{{ route('campaigns.show', $campaign->slug) }}"
   class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 block group shadow-sm">

    {{-- Image --}}
    <div class="aspect-video overflow-hidden bg-gradient-to-br from-blue-100 to-blue-200 relative">
        @if($campaign->featured_image)
            <img src="{{ Storage::url($campaign->featured_image) }}"
                 alt="{{ $campaign->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <span class="text-5xl opacity-40">{{ $icon }}</span>
            </div>
        @endif

        {{-- Status badge --}}
        @if($campaign->status === 'completed')
            <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                ✓ Completed
            </div>
        @elseif($campaign->days_remaining <= 7)
            <div class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-pulse">
                {{ $campaign->days_remaining }}d left!
            </div>
        @endif
    </div>

    <div class="p-5">
        {{-- Category --}}
        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $colorClass }}">
            {{ $icon }} {{ ucfirst($campaign->category) }}
        </span>

        {{-- Title --}}
        <h3 class="mt-2.5 text-base font-bold text-slate-900 line-clamp-2 group-hover:text-blue-600 transition-colors leading-snug">
            {{ $campaign->title }}
        </h3>

        {{-- Description --}}
        <p class="mt-1.5 text-sm text-slate-500 line-clamp-2 leading-relaxed">
            {{ $campaign->description }}
        </p>

        {{-- Progress --}}
        <div class="mt-4">
            <div class="flex justify-between items-center text-xs mb-1.5">
                <span class="font-semibold text-slate-700">
                    {{ number_format($campaign->raised_amount) }} EGP
                </span>
                <span class="font-bold {{ $campaign->progress_percentage >= 100 ? 'text-green-600' : 'text-blue-600' }}">
                    {{ $campaign->progress_percentage }}%
                </span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="{{ $progressColor }} h-2 rounded-full transition-all duration-700"
                     style="width: {{ min(100, $campaign->progress_percentage) }}%">
                </div>
            </div>
            <div class="flex justify-between text-xs text-slate-400 mt-1.5">
                <span>of {{ number_format($campaign->goal_amount) }} EGP goal</span>
                <span>{{ $campaign->donations_count }} donors</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-1.5 text-xs text-slate-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $campaign->is_expired ? 'Ended' : $campaign->days_remaining . ' days left' }}
            </div>
            <span class="text-xs font-semibold text-blue-600 group-hover:underline">
                Donate →
            </span>
        </div>
    </div>
</a>