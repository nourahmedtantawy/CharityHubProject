@component('mail::message')
# Thank You, {{ $donation->donor_name }}!

Your generous donation of **{{ number_format($donation->amount) }} {{ $donation->currency }}** to **{{ $donation->campaign->title }}** has been received and will make a real difference.

@component('mail::panel')
**Donation Details**
- Amount: {{ number_format($donation->amount) }} {{ $donation->currency }}
- Campaign: {{ $donation->campaign->title }}
- Date: {{ $donation->donated_at->format('d M Y') }}
@endcomponent

Your personalised donor certificate will be attached shortly.

@component('mail::button', ['url' => route('campaigns.show', $donation->campaign->slug), 'color' => 'green'])
View Campaign
@endcomponent

Thanks for making the world a better place.

**The CharityHub Team**
@endcomponent