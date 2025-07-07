<?php
namespace App\Exports;

use App\Models\EmailLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmailLogsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return EmailLog::latest()->get();
    }

    public function headings(): array
    {
        return [
            'Asset No',
            'Department',
            'Description',
            'Next Due Date',
            'Sent Date',
            'Sent Time',
            'Status',
            'Recipient Email',
            'Subject',
            'Email Body',
        ];
    }

    public function map($log): array
    {
        return [
            $log->asset_no,
            $log->department ?? 'N/A',
            $log->description ?? 'N/A',
            $log->next_due_date ?? 'N/A',
            $log->sent_date,
            $log->sent_time,
            $log->is_sent ? 'Success' : 'Failed',
            $log->recipient_email,
            $log->email_subject,
            nl2br(strip_tags($log->email_body)),
        ];
    }
}