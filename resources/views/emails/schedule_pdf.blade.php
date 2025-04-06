<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules List</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }

        .heading {
            text-align: center;
            font-size: 25px;
        }

        .watermark {
            position: fixed;
            top: 35%;
            left: 5%;
            opacity: 0.12;
            transform: rotate(-45deg);
            z-index: -1;
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #313131;
            padding: 8px 4px;
            font-size: 10px;
            word-break: break-word;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .header-table td {
            border: none;
        }

        .header-table img {
            display: block;
            margin: 8px auto 0;
        }

        hr {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    {{-- Watermark --}}
    <div class="watermark">
        <img src="{{ public_path('assets/images/4emus.png') }}" style="width: 600px;">
    </div>

    <div class="content">
        {{-- Header Logos and Title --}}
        <table class="header-table">
            <tr>
                <td style="width: 20%; text-align: center;">
                    <img src="{{ public_path('assets/images/koormal.png') }}" alt="Left Logo" width="130">
                </td>
                <td style="width: 60%; text-align: center;">
                    <h1 class="heading">Schedules List</h1>
                </td>
                <td style="width: 20%; text-align: center;">
                    <img src="{{ public_path('assets/images/4emus.png') }}" alt="Right Logo" width="130">
                </td>
            </tr>
        </table>

        <hr>

        {{-- Schedule Table --}}
        <table>
            <thead>
                <tr>
                    <th>Asset Number</th>
                    <th>Description</th>
                    <th>Department</th>
                    <th>Next Due Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->asset_no }}</td>
                        <td>{{ $schedule->description }}</td>
                        <td>{{ $schedule->department }}</td>
                        <td>{{ $schedule->next_due_date }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No schedules found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
