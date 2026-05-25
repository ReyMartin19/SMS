<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #111;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #111;
        }
        .header h2 {
            margin: 4px 0 0 0;
            font-size: 12px;
            font-weight: normal;
            color: #555;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-container {
            margin-bottom: 15px;
            background: #fdfdfd;
            border: 1px solid #e0e0e0;
            padding: 10px;
            border-radius: 4px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 5px;
            vertical-align: top;
        }
        .meta-table td.label {
            font-weight: bold;
            width: 18%;
            color: #444;
        }
        .meta-table td.value {
            width: 32%;
            color: #222;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #bbb;
            padding: 7px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            color: #111;
            text-transform: uppercase;
            font-size: 10px;
        }
        .data-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .summary-box {
            margin-top: 15px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: space-between;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .footer-left {
            float: left;
        }
        .footer-right {
            float: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoPath = \App\Models\SystemSetting::get('school_logo');
            $logoAbsPath = $logoPath ? storage_path('app/public/' . $logoPath) : null;
        @endphp
        @if($logoAbsPath && file_exists($logoAbsPath))
            <img src="{{ $logoAbsPath }}" alt="School Logo" style="max-height: 60px; margin-bottom: 6px; object-fit: contain;" />
        @endif
        <h1>{{ setting('school_name', 'School Management System') }}</h1>
        <h2>Official Student Directory</h2>
    </div>

    <div class="report-title">Active Student List</div>

    <div class="meta-container">
        <table class="meta-table">
            <tr>
                <td class="label">Status:</td>
                <td class="value">{{ $filters['status'] ? ucfirst($filters['status']) : 'All' }}</td>
                <td class="label">Gender:</td>
                <td class="value">{{ $filters['gender'] ? ucfirst($filters['gender']) : 'All' }}</td>
            </tr>
            <tr>
                <td class="label">Grade Level:</td>
                <td class="value">{{ $filters['grade_level_name'] ?? 'All' }}</td>
                <td class="label">Total Listed:</td>
                <td class="value">{{ count($students) }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Student Name</th>
                <th style="width: 15%;">LRN</th>
                <th style="width: 10%;">Gender</th>
                <th style="width: 12%;">Birthdate</th>
                <th style="width: 23%;">Address</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $student->last_name }}</strong>, 
                        {{ $student->first_name }} 
                        {{ $student->middle_name ?? '' }} 
                        {{ $student->suffix ?? '' }}
                    </td>
                    <td>{{ $student->lrn ?? 'N/A' }}</td>
                    <td>{{ ucfirst($student->gender) }}</td>
                    <td>{{ $student->birthdate ? $student->birthdate->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $student->address ?? 'N/A' }}</td>
                    <td>{{ ucfirst($student->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #666; padding: 20px;">No students found matching the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary-box">
        Total Students: {{ count($students) }}
    </div>

    <div class="footer clearfix">
        <div class="footer-left">Generated on: {{ now()->format('F d, Y h:i A') }}</div>
        <div class="footer-right">Confidential - For Official School Use Only</div>
    </div>
</body>
</html>
