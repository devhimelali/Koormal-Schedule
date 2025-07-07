<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Email Log Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.5;
            margin: 30px;
        }

        .watermark {
            position: fixed;
            top: 25%;
            left: 10%;
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

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 10px;
            vertical-align: top;
        }

        th {
            background-color: #f7f7f7;
            font-weight: bold;
            text-align: left;
        }

        .badge {
            display: inline-block;
            padding: 0px 8px 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            color: #fff;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-failed {
            background-color: #dc3545;
        }

        .email-body {
            padding: 10px;
            background-color: #fafafa;
            border: 1px solid #e1e1e1;
            font-size: 10px;
            margin-top: 5px;
        }

        .section {
            page-break-inside: avoid;
            margin-bottom: 25px;
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
                <div class="title">EMAIL LOG REPORT</div>
            </td>
            <td style="width: 20%;">
                <img src="{{ public_path('assets/images/4emus.png') }}" class="logo" alt="Right Logo">
            </td>
        </tr>
    </table>

    @foreach ($logs as $index => $log)
        <div class="section">
            <table>
                <tr>
                    <th style="width: 15%;">#</th>
                    <td>{{ $index + 1 }}</td>
                </tr>
                <tr>
                    <th>Asset No</th>
                    <td>{{ $log->asset_no }}</td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td>{{ $log->department ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Next Due Date</th>
                    <td>{{ $log->next_due_date ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Sent Date</th>
                    <td>{{ $log->sent_date }}</td>
                </tr>
                <tr>
                    <th>Sent Time</th>
                    <td>{{ $log->sent_time }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge {{ $log->is_sent ? 'badge-success' : 'badge-failed' }}">
                            {{ $log->is_sent ? 'Success' : 'Failed' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Recipient</th>
                    <td>{{ $log->recipient_email }}</td>
                </tr>
                <tr>
                    <th>Subject</th>
                    <td>{{ $log->email_subject }}</td>
                </tr>
                <tr>
                    <th>Email Body</th>
                    <td>
                        <div class="email-body">
                            {!! $log->email_body !!}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
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
