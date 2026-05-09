@extends('layouts.app')
@section('title', 'Thank You!')
@section('content')
<div class="max-w-lg mx-auto text-center py-16">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-3">Thank You!</h1>
    <p class="text-gray-500 mb-2">Your donation of
        <strong>{{ number_format($donation?->amount) }} {{ $donation?->currency }}</strong>
        has been received.
    </p>
    <p class="text-gray-400 text-sm mb-8">A certificate will be emailed to {{ $donation?->donor_email }} shortly.</p>
    <a href="{{ route('campaigns.index') }}"
       class="bg-green-600 text-white px-8 py-3 rounded-xl hover:bg-green-700 transition-colors">
        Browse More Campaigns
    </a>
</div>
@endsection