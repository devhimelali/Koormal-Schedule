<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Missed Safety Inspection – Vehicle NO SHOW</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background-color: #f5f5f5;
            padding: 12px;
            border-bottom: 2px solid #ddd;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
            color: #222;
        }

        .content {
            padding: 16px;
        }

        .vehicle-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        .vehicle-table th, .vehicle-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        .vehicle-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <h2>Missed Safety Inspection Notification</h2>
</div>

<div class="content">
    <p>Dear {{ $department->name }},</p>

    <p>
        The following Safety Inspection schedule was missed today - <strong>{{$schedule->asset_no }}</strong>
        {{$schedule->description }} <strong>{{ $schedule->next_due_date}}</strong>
    </p>
    <p>
        The vehicle is now non compliant with site safety requirements and may be unsafe for use.
    </p>

</div>

<div class="footer">
    <p>
        &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
    </p>
</div>
</body>
</html>
