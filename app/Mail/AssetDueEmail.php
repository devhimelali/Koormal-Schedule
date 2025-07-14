<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssetDueEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $asset_no;
    public $emailBody;
    public $next_due_date;

    /**
     * Create a new message instance.
     */
    public function __construct(
        $subject,
        $asset_no,
        $emailBody,
        $next_due_date
    ) {
        $this->subject = $subject;
        $this->asset_no = $asset_no;
        $this->emailBody = $emailBody;
        $this->next_due_date = $next_due_date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.asset_due',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
