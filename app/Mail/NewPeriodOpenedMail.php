<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPeriodOpenedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $period
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Form Pendataan Dibuka: ' . $this->period,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-period-opened',
        );
    }
}
