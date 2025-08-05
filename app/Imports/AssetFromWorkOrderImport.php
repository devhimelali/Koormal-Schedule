<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\PumpSchedule;
use App\Models\TruckSchedule;
use Illuminate\Support\Collection;
use App\Models\LightVehicleSchedule;
use App\Models\LightingTowerSchedule;
use App\Models\ForkliftManitouSchedule;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AssetFromWorkOrderImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, WithStartRow
{

    public function __construct(public string $type)
    {
        //
    }

    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param  Collection  $rows
     */
    public function collection(Collection $rows)
    {
        $model = $this->getScheduleModel($this->type);
        foreach ($rows->reverse() as $row) {
            // Skip entirely empty rows
            if (empty(array_filter($row->toArray()))) {
                continue;
            }

            if (empty($row['asset_no'])) {
                continue;
            }

            $model::create([
                'asset_no' => $row['asset_no'] ?? null,
                'department' => $row['department'] ?? null,
                'next_due_date' => $this->parseExcelDate($row['due_start']),
                'description' => $row['asset_description'] ?? null,
                'is_technician_entry' => 1,
                'status' => 'no status yet',
                'is_today_works' => 1,
            ]);

        }
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }

    private function parseExcelDate($value, $format = 'd-m-Y')
    {
        return isset($value)
            ? Carbon::instance(Date::excelToDateTimeObject((float) $value))->format($format)
            : null;
    }

    private function getScheduleModel($type): string
    {
        return match ($type) {
            'lv' => LightVehicleSchedule::class,
            'lt' => LightingTowerSchedule::class,
            'tk' => TruckSchedule::class,
            'fm' => ForkliftManitouSchedule::class,
            'pm' => PumpSchedule::class,
        };
    }
}
