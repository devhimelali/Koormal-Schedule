<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatusLogExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithStyles
{
    protected $statuses;
    protected $title;

    /**
     * StatusLogExport constructor.
     *
     * @param $statuses
     * @param string $title
     */
    public function __construct($statuses, $title = 'Status Logs List')
    {
        $this->statuses = $statuses;
        $this->title = $title;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->statuses)->map(function ($status) {
            return [
                'Asset No' => $status->asset_no,
                'Description' => $status->description ?? '',
                'Next Due Date' => $status->next_due_date ?? '',
                'Change Date' => $status->change_date,
                'Change Time' => $status->change_time,
                'Old Status' => ucwords($status->old_status),
                'New Status' => ucwords($status->new_status),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Asset No',
            'Description',
            'Next Due Date',
            'Change Date',
            'Change Time',
            'Old Status',
            'New Status',
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
