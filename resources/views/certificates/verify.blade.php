@extends('layouts.app')
@section('title', 'Certificate Verified')
@section('content')
<div class="max-w-lg mx-auto text-center py-16">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Certificate Verified</h1>
    <p class="text-gray-500 mb-6">This is an authentic CharityHub donation certificate.</p>
    <div class="bg-gray-50 rounded-xl p-6 text-left space-y-3">
        <div class="flex justify-between"><span class="text-gray-500">Donor</span><strong>{{ $cert->donation->donor_name }}</strong></div>
        <div class="flex justify-between"><span class="text-gray-500">Amount</span><strong>{{ number_format($cert->donation->amount) }} {{ $cert->donation->currency }}</strong></div>
        <div class="flex justify-between"><span class="text-gray-500">Campaign</span><strong>{{ $cert->donation->campaign->title }}</strong></div>
        <div class="flex justify-between"><span class="text-gray-500">Certificate No.</span><strong>{{ $cert->certificate_number }}</strong></div>
        <div class="flex justify-between"><span class="text-gray-500">Issued</span><strong>{{ $cert->issued_at->format('d M Y') }}</strong></div>
    </div>
</div>
@endsection