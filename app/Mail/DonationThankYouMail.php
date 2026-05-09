<?php
namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Donation $donation) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Thank You for Your Donation — CharityHub');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.donation-thanks', with: [
            'donation' => $this->donation,
        ]);
    }
}