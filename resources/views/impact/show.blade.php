@extends('layouts.app')
@section('title', $report->title)
@section('content')

<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $report->title }}</h1>
        <p class="text-gray-500 mt-2">{{ $report->summary }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-green-50 rounded-xl p-5 text-center">
            <p class="text-3xl font-bold text-green-700">{{ number_format($report->beneficiaries_count) }}</p>
            <p class="text-sm text-green-600 mt-1">Beneficiaries</p>
        </div>
        <div class="bg-blue-50 rounded-xl p-5 text-center">
            <p class="text-3xl font-bold text-blue-700">{{ $report->campaign->donations()->where('status','completed')->count() }}</p>
            <p class="text-sm text-blue-600 mt-1">Donors</p>
        </div>
        <div class="bg-purple-50 rounded-xl p-5 text-center">
            <p class="text-3xl font-bold text-purple-700">{{ number_format($report->campaign->raised_amount) }}</p>
            <p class="text-sm text-purple-600 mt-1">{{ $report->campaign->currency }} Raised</p>
        </div>
    </div>

    {{-- Google Map --}}
    @if($report->beneficiaries->count())
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Beneficiary Locations</h2>
        <div id="map" class="w-full h-80 rounded-2xl border border-gray-200"></div>
    </div>
    @endif

    {{-- Photo gallery --}}
    @if($report->photos->count())
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-3">Photo Gallery</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @foreach($report->photos as $photo)
                <div class="aspect-square overflow-hidden rounded-xl bg-gray-100">
                    <img src="{{ Storage::url($photo->path) }}"
                         alt="{{ $photo->caption }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Rich content --}}
    @if($report->content)
    <div class="prose max-w-none">{!! $report->content !!}</div>
    @endif
</div>

@if($report->beneficiaries->count())
<script>
function initMap() {
    const beneficiaries = @json($report->beneficiaries);
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: { lat: parseFloat(beneficiaries[0].latitude), lng: parseFloat(beneficiaries[0].longitude) },
        styles: [{ featureType: 'poi', stylers: [{ visibility: 'off' }] }],
    });
    beneficiaries.forEach(b => {
        new google.maps.Marker({
            position: { lat: parseFloat(b.latitude), lng: parseFloat(b.longitude) },
            map,
            title: b.location_name,
        });
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap" async defer></script>
@endif
@endsection