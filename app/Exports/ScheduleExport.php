<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    protected $schedules;
    protected $title;

    public function __construct($schedules, $title = 'Schedule List')
    {
        $this->schedules = $schedules;
        $this->title = $title;
    }

    public function collection()
    {
        return collect($this->schedules)->map(function ($schedule) {
            return [
                'Asset No' => $schedule->asset_no,
                'Department' => $schedule->department ?? '',
                'Next Due Date' => $schedule->next_due_date,
                'Description' => $schedule->description ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asset No',
            'Department',
            'Next Due Date',
            'Description',
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '356854',
                    ],
                ],
            ],
        ];
    }
}
