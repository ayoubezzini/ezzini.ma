<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactMessageMail extends Mailable
{
    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public ?string $company,
        public string $messageBody,
        public string $visitorLocale,
    ) {}

    public function envelope(): Envelope
    {
        $from = $this->company ? "{$this->senderName} ({$this->company})" : $this->senderName;

        return new Envelope(
            replyTo: [new Address($this->senderEmail, $this->senderName)],
            subject: "ezzini.ma · {$from}",
        );
    }

    public function content(): Content
    {
        return new Content(text: 'mail.contactMessage');
    }
}
