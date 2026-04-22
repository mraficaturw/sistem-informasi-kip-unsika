<?php

namespace App\Mail;

use App\Models\KhsSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KhsRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public KhsSubmission $submission
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendataan KHS Ditolak',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.khs-rejected',
        );
    }
}
