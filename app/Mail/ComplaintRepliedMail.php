<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintRepliedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Complaint $complaint
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengaduan Anda Telah Ditanggapi',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.complaint-replied',
        );
    }
}
