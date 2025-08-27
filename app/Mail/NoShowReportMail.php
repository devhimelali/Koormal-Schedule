<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoShowReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;
    public $department;

    /**
     * Create a new message instance.
     */
    public function __construct($department, $schedule)
    {
        $this->department = $department;
        $this->schedule = $schedule;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->schedule->asset_no . ' '. $this->schedule->description . ' ' . $this->schedule->next_due_date,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.no_show_report',
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
