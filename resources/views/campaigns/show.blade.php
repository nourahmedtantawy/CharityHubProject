@extends('layouts.app')

@section('title', $campaign->meta_title ?? $campaign->title)
@section('meta_description', $campaign->meta_description ?? $campaign->description)
@section('og_title', $campaign->title)
@section('og_description', $campaign->description)
@section('og_image', $campaign->featured_image ? Storage::url($campaign->featured_image) : '')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- LEFT: Campaign details --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Featured image --}}
        @if($campaign->featured_image)
            <img src="{{ Storage::url($campaign->featured_image) }}"
                 alt="{{ $campaign->title }}"
                 class="w-full rounded-2xl object-cover max-h-96">
        @endif

        {{-- Title & category --}}
        <div>
            @if($campaign->category)
                <span class="text-sm font-medium text-green-700 bg-green-50 px-3 py-1 rounded-full">
                    {{ ucfirst($campaign->category) }}
                </span>
            @endif
            <h1 class="text-3xl font-bold text-gray-900 mt-3">{{ $campaign->title }}</h1>
            <p class="text-gray-500 mt-2">{{ $campaign->description }}</p>
        </div>

        {{-- Rich content --}}
        @if($campaign->content)
            <div class="prose max-w-none text-gray-700">
                {!! $campaign->content !!}
            </div>
        @endif

        {{-- Recent donations feed --}}
        @if($recentDonations->count())
            <div class="bg-white rounded-2xl border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4">Recent Supporters</h3>
                <div class="space-y-3" id="donations-feed">
                    @foreach($recentDonations as $donation)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-sm">
                                {{ strtoupper(substr($donation->donor_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $donation->donor_name }}</p>
                                <p class="text-xs text-gray-400">
                                    donated {{ number_format($donation->amount) }} EGP
                                    · {{ $donation->donated_at?->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════ --}}
        {{-- DONATION FORM                                   --}}
        {{-- ═══════════════════════════════════════════════ --}}
        <div id="donate" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Make a Donation</h2>
            <p class="text-gray-400 text-sm mb-6">100% of your donation goes directly to this campaign.</p>

            {{-- Error message --}}
            @if(session('error'))
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('donations.store', $campaign) }}" method="POST" class="space-y-5">
                @csrf

                {{-- Name & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="donor_name"
                            required
                            value="{{ old('donor_name', auth()->user()?->name) }}"
                            placeholder="Ahmed Mohamed"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('donor_name') border-red-400 @enderror"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="donor_email"
                            required
                            value="{{ old('donor_email', auth()->user()?->email) }}"
                            placeholder="ahmed@example.com"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('donor_email') border-red-400 @enderror"
                        >
                    </div>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone (optional)</label>
                    <input
                        type="text"
                        name="donor_phone"
                        value="{{ old('donor_phone') }}"
                        placeholder="+20 10 0000 0000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                    >
                </div>

                {{-- Amount preset buttons + custom input --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Donation Amount ({{ $campaign->currency }}) <span class="text-red-500">*</span>
                    </label>

                    {{-- Preset amounts --}}
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach([100, 250, 500, 1000, 2500, 5000] as $preset)
                            <button
                                type="button"
                                onclick="setAmount({{ $preset }})"
                                class="preset-btn px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:border-green-500 hover:text-green-600 hover:bg-green-50 transition-colors"
                            >
                                {{ number_format($preset) }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Custom amount --}}
                    <input
                        type="number"
                        name="amount"
                        id="amount-input"
                        required
                        min="5"
                        step="1"
                        value="{{ old('amount') }}"
                        placeholder="Or enter a custom amount..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 @error('amount') border-red-400 @enderror"
                    >
                    <p class="text-xs text-gray-400 mt-1">Minimum donation: 5 {{ $campaign->currency }}</p>
                </div>

                {{-- Donation type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Donation Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="type-option flex items-center gap-3 border border-gray-300 rounded-lg p-3 cursor-pointer transition-colors hover:border-green-400">
                            <input
                                type="radio"
                                name="type"
                                value="one_time"
                                checked
                                class="text-green-600"
                                onchange="toggleFrequency(false)"
                            >
                            <div>
                                <p class="text-sm font-medium text-gray-800">One-time</p>
                                <p class="text-xs text-gray-400">Single donation</p>
                            </div>
                        </label>
                        <label class="type-option flex items-center gap-3 border border-gray-300 rounded-lg p-3 cursor-pointer transition-colors hover:border-green-400">
                            <input
                                type="radio"
                                name="type"
                                value="recurring"
                                class="text-green-600"
                                onchange="toggleFrequency(true)"
                            >
                            <div>
                                <p class="text-sm font-medium text-gray-800">Recurring</p>
                                <p class="text-xs text-gray-400">Auto-renews</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Frequency (shown only when recurring is selected) --}}
                <div id="frequency-row" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Billing Frequency</label>
                    <select
                        name="frequency"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                    >
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>

                {{-- Payment gateway --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 border border-gray-300 rounded-lg p-3 cursor-pointer transition-colors hover:border-green-400">
                            <input
                                type="radio"
                                name="gateway"
                                value="stripe"
                                checked
                                class="text-green-600"
                            >
                            <div>
                                <p class="text-sm font-medium text-gray-800">Card (Stripe)</p>
                                <p class="text-xs text-gray-400">Visa, Mastercard, Amex</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 border border-gray-300 rounded-lg p-3 cursor-pointer transition-colors hover:border-green-400">
                            <input
                                type="radio"
                                name="gateway"
                                value="paymob"
                                class="text-green-600"
                            >
                            <div>
                                <p class="text-sm font-medium text-gray-800">PayMob</p>
                                <p class="text-xs text-gray-400">Egypt local payment</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Message --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Message of Support <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea
                        name="message"
                        rows="3"
                        maxlength="500"
                        placeholder="Leave an encouraging message for this campaign..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 resize-none"
                    >{{ old('message') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Max 500 characters</p>
                </div>

                {{-- Anonymous toggle --}}
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input
                        type="checkbox"
                        name="is_anonymous"
                        value="1"
                        {{ old('is_anonymous') ? 'checked' : '' }}
                        class="w-4 h-4 rounded text-green-600 border-gray-300 focus:ring-green-400"
                    >
                    <div>
                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Donate anonymously</p>
                        <p class="text-xs text-gray-400">Your name won't appear in the public donors list</p>
                    </div>
                </label>

                {{-- Summary box --}}
                <div id="donation-summary" class="bg-green-50 border border-green-100 rounded-xl p-4 hidden">
                    <p class="text-sm font-medium text-green-800 mb-1">Donation Summary</p>
                    <div class="flex justify-between text-sm text-green-700">
                        <span>Amount</span>
                        <strong id="summary-amount">—</strong>
                    </div>
                    <div class="flex justify-between text-sm text-green-700">
                        <span>Campaign</span>
                        <strong>{{ Str::limit($campaign->title, 30) }}</strong>
                    </div>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 active:scale-95 text-white font-semibold py-3.5 rounded-xl transition-all text-base flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Proceed to Payment
                </button>

                {{-- Trust badges --}}
                <div class="flex items-center justify-center gap-4 pt-2">
                    <div class="flex items-center gap-1 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1l3.09 6.26L22 8.27l-5 4.87 1.18 6.88L12 16.77l-6.18 3.25L7 13.14 2 8.27l6.91-1.01L12 1z"/>
                        </svg>
                        Secure payment
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        SSL encrypted
                    </div>
                    <div class="flex items-center gap-1 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Certificate issued
                    </div>
                </div>

            </form>
        </div>
        {{-- ═══════════════════════════════════════════════ --}}
        {{-- END DONATION FORM                              --}}
        {{-- ═══════════════════════════════════════════════ --}}

    </div>

    {{-- RIGHT: Sticky donation sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">

            <livewire:campaign-progress :campaign="$campaign" />

            {{-- Donate button --}}
            <a href="#donate"
               class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-semibold py-3 rounded-xl transition-colors mt-5">
                Donate Now
            </a>

            {{-- Social sharing --}}
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center mb-3">Share this campaign</p>
                <div class="flex justify-center gap-3">

                    {{-- Twitter/X --}}
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($campaign->title) }}&url={{ urlencode(request()->url()) }}"
                       target="_blank"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-500 hover:text-blue-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                       target="_blank"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-blue-50 text-gray-500 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . request()->url()) }}"
                       target="_blank"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-green-50 text-gray-500 hover:text-green-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>

                    {{-- Copy link --}}
                    <button onclick="copyLink()"
                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// ── Copy link ─────────────────────────────────────────────
function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copied to clipboard!');
    });
}

// ── Preset amount buttons ─────────────────────────────────
function setAmount(value) {
    const input = document.getElementById('amount-input');
    input.value = value;

    // Highlight selected preset
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
        btn.classList.add('border-gray-300', 'text-gray-600');
    });
    event.target.classList.add('border-green-500', 'text-green-600', 'bg-green-50');
    event.target.classList.remove('border-gray-300', 'text-gray-600');

    updateSummary(value);
}

// ── Show donation summary box as user types ───────────────
document.getElementById('amount-input').addEventListener('input', function () {
    // Clear preset highlights when typing custom amount
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.classList.remove('border-green-500', 'text-green-600', 'bg-green-50');
        btn.classList.add('border-gray-300', 'text-gray-600');
    });
    updateSummary(this.value);
});

function updateSummary(amount) {
    const summary = document.getElementById('donation-summary');
    const summaryAmount = document.getElementById('summary-amount');
    const currency = '{{ $campaign->currency }}';

    if (amount && parseFloat(amount) >= 5) {
        summary.classList.remove('hidden');
        summaryAmount.textContent = Number(amount).toLocaleString() + ' ' + currency;
    } else {
        summary.classList.add('hidden');
    }
}

// ── Toggle frequency row for recurring donations ──────────
function toggleFrequency(show) {
    const row = document.getElementById('frequency-row');
    if (show) {
        row.classList.remove('hidden');
    } else {
        row.classList.add('hidden');
    }
}
</script>

@endsection