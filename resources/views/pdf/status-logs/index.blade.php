<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Status Logs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .watermark {
            position: fixed;
            top: 35%;
            left: 5%;
            opacity: 0.08;
            transform: rotate(-45deg);
            z-index: -1;
        }

        .watermark img {
            width: 800px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .header-table td {
            text-align: center;
            vertical-align: middle;
        }

        .header-table .logo {
            width: 130px;
            margin: auto;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #222;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        table.data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .footer {
            text-align: left;
            font-size: 12px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('assets/images/4emus.png') }}" alt="Watermark">
    </div>

    <table class="header-table">
        <tr>
            <td style="width: 20%;">
                <img src="{{ public_path('assets/images/koormal.png') }}" class="logo" alt="Left Logo">
            </td>
            <td style="width: 60%;">
                <div class="title">Status Logs</div>
            </td>
            <td style="width: 20%;">
                <img src="{{ public_path('assets/images/4emus.png') }}" class="logo" alt="Right Logo">
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 130px;">Asset Number</th>
                <th style="width: 160px;">Description</th>
                <th>Next Due Date</th>
                <th>Change Date</th>
                <th>Change Time</th>
                <th>Old Status</th>
                <th>New Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $log)
                <tr>
                    <td>{{ $log->asset_no }}</td>
                    <td>{{ $log->description ?? '-' }}</td>
                    <td>{{ $log->next_due_date ?? '-' }}</td>
                    <td>{{ $log->change_date }}</td>
                    <td>{{ $log->change_time }}</td>
                    <td>{{ ucwords($log->old_status) }}</td>
                    <td>{{ ucwords($log->new_status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No data available for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->timezone(config('app.timezone'))->format('d-m-Y h:i A') }}
    </div>
    <script type="text/php">
    if (isset($pdf)) {
        $pdf->page_script('
            $text = __("Page :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
            $font = null;
            $size = 9;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default

            // Compute text width to center correctly
            $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

            $x = ($pdf->get_width() - $textWidth) - 38;
            $y = $pdf->get_height() - 35;

            $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
        ');
    }
</script>
</body>

</html>
