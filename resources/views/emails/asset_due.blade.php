<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Vehicle Maintenance Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 25px;
            border: 1px solid #ddd;
            background-color: #fafafa;
        }

        .header {
            background-color: #004080;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
        }

        .content {
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

        .highlight {
            font-weight: bold;
            color: #004080;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            Vehicle Maintenance Notice
        </div>

        <div class="content">
            <p>Dear Client,</p>

            <p>We would like to inform you that the vehicle with Asset Number
                <span class="highlight">{{ $asset_no }}</span> is scheduled for maintenance.
            </p>

            <p><strong>Next Due Date:</strong> {{ $next_due_date }}</p>

            @if (!empty($emailBody))
                {!! $emailBody !!}
            @endif


            <p>If you have any questions or require further assistance, please don't hesitate to contact us.</p>

            <p>Thank you,<br>
                <em>The Maintenance Team</em>
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
        </div>
    </div>
</body>

</html>
