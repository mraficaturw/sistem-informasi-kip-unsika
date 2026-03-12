<?php

namespace App\Mail;

use App\Models\KhsSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KhsVerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public KhsSubmission $submission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'KHS Anda Telah Diverifikasi',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.khs-verified',
        );
    }
}
