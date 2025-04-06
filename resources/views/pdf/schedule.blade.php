<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Schedules List</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .heading {
            text-align: center;
            font-size: 34px;
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
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('assets/images/4emus.png') }}" style="width: 900px;;">
    </div>
    <div class="content">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tbody>
                <tr>
                    <!-- Left Logo -->
                    <td style="width: 20%; text-align: center; vertical-align: middle;">
                        <img src="{{ public_path('assets/images/koormal.png') }}" alt="Logo" width="130"
                            style="margin: 8px auto 0; display: block;">
                    </td>

                    <!-- Center Heading -->
                    <td style="width: 60%; text-align: center; vertical-align: middle;">
                        <h1 style="margin: 0; font-size: 24px; font-weight: 600;">Schedules List</h1>
                    </td>

                    <!-- Right Logo -->
                    <td style="width: 20%; text-align: center; vertical-align: middle;">
                        <img src="{{ public_path('assets/images/4emus.png') }}" alt="Logo" width="130"
                            style="margin: 8px auto 0; display: block;">
                    </td>
                </tr>
            </tbody>
        </table>

        <hr style="margin-bottom: 30px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="font-size: 20px;">
                <tr>
                    <th style="border: 1px solid #313131; padding: 8px; max-width: 140px; width: 140px;">Asset Number
                    </th>
                    <th style="border: 1px solid #313131; padding: 8px;">Description</th>
                    <th style="border: 1px solid #313131; padding: 8px; max-width: 180px; width: 180px;">Department</th>
                    <th
                        style="border: 1px solid #313131; padding: 8px; max-width: 130px; width: 130px; word-wrap: break-word;">
                        Next Due Date
                    </th>
                </tr>
            </thead>
            <tbody style="font-size: 18px; font-weight: normal;">
                @foreach ($schedules as $schedule)
                    <tr>
                        <td style="border: 1px solid #313131; padding: 8px;">{{ $schedule->asset_no }}</td>
                        <td style="border: 1px solid #313131; padding: 8px;">{{ $schedule->description }}</td>
                        <td style="border: 1px solid #313131; padding: 8px;">{{ $schedule->department }}</td>
                        <td style="border: 1px solid #313131; padding: 8px;">{{ $schedule->next_due_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
