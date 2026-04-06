<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from'),
            subject: 'Application Received – Ref: ' . $this->application->ack_ref_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-acknowledgement',
        );
    }
}
