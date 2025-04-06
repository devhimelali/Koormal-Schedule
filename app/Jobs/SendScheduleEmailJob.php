<?php

namespace App\Jobs;

use App\Mail\ScheduleListMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendScheduleEmailJob implements ShouldQueue
{
    use Queueable;
    public $email;
    public $subject;
    public $message;
    public $schedules;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $subject, $message, $schedules)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
        $this->schedules = $schedules;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new ScheduleListMail(
            $this->subject,
            $this->message,
            $this->schedules
        ));
    }
}
