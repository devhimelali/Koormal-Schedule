<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use PDF;

class ScheduleListMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $schedules;
    protected $pdfData;
    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $schedules)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->schedules = $schedules;
        // Generate the PDF and store the data
        $this->pdfData = PDF::loadView('emails.schedule_pdf', [
            'schedules' => $schedules
        ])->output();
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
            markdown: 'emails.schedule',
            with: [
                'subject' => $this->subject,
                'body' => $this->body,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn() => $this->pdfData, 'schedule_list.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
