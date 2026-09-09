<?php

namespace App\Mail;

use App\Models\Dokumen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentExpirationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Dokumen $dokumen
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Peringatan Dokumen Kendaraan',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.document-expiration',
        );
    }
}