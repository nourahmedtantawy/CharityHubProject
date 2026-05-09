<div>
    {{-- Raised amount --}}
    <p class="text-3xl font-bold text-gray-900">
        {{ number_format($raised) }}
        <span class="text-base font-normal text-gray-400">EGP</span>
    </p>
    <p class="text-sm text-gray-500 mt-1">
        raised of {{ number_format($goal) }} EGP goal
    </p>

    {{-- Animated progress bar --}}
    <div class="w-full bg-gray-100 rounded-full h-3 my-3">
        <div class="bg-green-500 h-3 rounded-full transition-all duration-700"
             style="width: {{ $percentage }}%">
        </div>
    </div>

    <div class="flex justify-between text-xs text-gray-400">
        <span>{{ $percentage }}% funded</span>
        <span>{{ $donorCount }} donors</span>
    </div>
</div>