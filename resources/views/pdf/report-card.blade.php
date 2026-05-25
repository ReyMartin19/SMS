<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Official Report Card - {{ $student->last_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .report-card-container {
            border: 2px solid #111;
            padding: 20px;
            position: relative;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px double #111;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header h2 {
            margin: 3px 0 0 0;
            font-size: 11px;
            font-weight: normal;
            color: #444;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 2px 0 0 0;
            font-size: 10px;
            font-weight: normal;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #111;
            padding-bottom: 3px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            width: 15%;
            color: #333;
        }
        .info-table td.value {
            border-bottom: 1px solid #777;
            width: 35%;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #111;
            padding: 6px 8px;
            text-align: center;
        }
        .grades-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .grades-table td.subject-name {
            text-align: left;
            font-weight: bold;
        }
        .grades-table td.remarks-pass {
            color: green;
            font-weight: bold;
        }
        .grades-table td.remarks-fail {
            color: red;
            font-weight: bold;
        }
        .summary-panel {
            width: 100%;
            border: 1px solid #111;
            margin-bottom: 20px;
            background: #fafafa;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 12px;
            font-size: 12px;
        }
        .summary-table td.label {
            font-weight: bold;
            text-align: right;
            width: 35%;
            border-right: 1px solid #111;
        }
        .summary-table td.value {
            font-weight: bold;
            text-align: left;
            width: 15%;
            font-size: 13px;
        }
        .status-promoted {
            color: green;
            text-transform: uppercase;
            font-size: 14px;
        }
        .status-retained {
            color: red;
            text-transform: uppercase;
            font-size: 14px;
        }
        .signatures-container {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box {
            float: left;
            width: 45%;
            text-align: center;
        }
        .signature-line {
            margin: 40px auto 5px auto;
            width: 80%;
            border-top: 1px solid #111;
        }
        .signature-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .signature-sub {
            font-size: 9px;
            color: #555;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px dotted #ccc;
            padding-top: 8px;
        }
        @media print {
            body {
                font-size: 10px;
            }
            .report-card-container {
                border: 2px solid #000;
            }
            .grades-table th {
                background-color: #eaeaea !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="report-card-container">
        @php
            $logoPath = \App\Models\SystemSetting::get('school_logo');
            $logoAbsPath = $logoPath ? storage_path('app/public/' . $logoPath) : null;
        @endphp
        <div class="header">
            @if($logoAbsPath && file_exists($logoAbsPath))
                <img src="{{ $logoAbsPath }}" alt="School Logo" style="max-height: 60px; margin-bottom: 6px; object-fit: contain;" />
            @endif
            <h1>{{ setting('school_name', 'School Management System') }}</h1>
            <h2>Student Progress Report Card</h2>
            <h3>{{ setting('school_address', 'Official Academic Transcript') }}</h3>
        </div>

        <div class="title">Student Profile & Academic Record</div>

        <table class="info-table">
            <tr>
                <td class="label">Student Name:</td>
                <td class="value">
                    <strong>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }} {{ $student->suffix ?? '' }}</strong>
                </td>
                <td class="label">LRN:</td>
                <td class="value"><strong>{{ $student->lrn }}</strong></td>
            </tr>
            <tr>
                <td class="label">Grade & Section:</td>
                <td class="value">{{ $gradeLevel->name ?? 'N/A' }} - {{ $section->name ?? 'N/A' }}</td>
                <td class="label">School Year:</td>
                <td class="value">{{ $schoolYear->name }}</td>
            </tr>
            <tr>
                <td class="label">Gender:</td>
                <td class="value">{{ ucfirst($student->gender) }}</td>
                <td class="label">Birthdate:</td>
                <td class="value">{{ $student->birthdate ? $student->birthdate->format('F d, Y') : 'N/A' }}</td>
            </tr>
        </table>

        <table class="grades-table">
            <thead>
                <tr>
                    <th style="text-align: left; width: 35%;">Learning Areas / Subjects</th>
                    <th style="width: 10%;">Q1</th>
                    <th style="width: 10%;">Q2</th>
                    <th style="width: 10%;">Q3</th>
                    <th style="width: 10%;">Q4</th>
                    <th style="width: 13%;">Final Grade</th>
                    <th style="width: 12%;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gradesData as $data)
                    <tr>
                        <td class="subject-name">{{ $data['subject_name'] }}</td>
                        <td>{{ $data['q1'] !== null ? number_format($data['q1'], 0) : '-' }}</td>
                        <td>{{ $data['q2'] !== null ? number_format($data['q2'], 0) : '-' }}</td>
                        <td>{{ $data['q3'] !== null ? number_format($data['q3'], 0) : '-' }}</td>
                        <td>{{ $data['q4'] !== null ? number_format($data['q4'], 0) : '-' }}</td>
                        <td><strong>{{ $data['final_grade'] !== null ? number_format($data['final_grade'], 1) : '-' }}</strong></td>
                        @if($data['remarks'] === 'Passed')
                            <td class="remarks-pass">PASSED</td>
                        @elseif($data['remarks'] === 'Failed')
                            <td class="remarks-fail">FAILED</td>
                        @else
                            <td style="color: #666;">-</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="color: #666; padding: 20px;">No subjects or grades assigned for this school year.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-panel">
            <table class="summary-table">
                <tr>
                    <td class="label">General Average:</td>
                    <td class="value">
                        {{ $overallAverage !== null ? number_format($overallAverage, 2) : '-' }}
                    </td>
                    <td class="label" style="border-left: 1px solid #111;">Promotion Status:</td>
                    <td style="padding: 8px 12px; font-weight: bold;">
                        @if($promotionStatus === 'Promoted')
                            <span class="status-promoted">PROMOTED</span>
                        @elseif($promotionStatus === 'Retained')
                            <span class="status-retained">RETAINED</span>
                        @else
                            <span style="color: #666; font-size: 13px;">INCOMPLETE</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="signatures-container clearfix">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-title">{{ $section->teacher_name ?? 'Class Adviser' }}</div>
                <div class="signature-sub">Class Adviser</div>
            </div>
            
            <div class="signature-box" style="float: right;">
                <div class="signature-line"></div>
                <div class="signature-title">{{ setting('principal_name', 'School Principal') }}</div>
                <div class="signature-sub">Principal / School Head</div>
            </div>
        </div>

        <div class="footer">
            {{ setting('report_card_footer', 'This is an official document of the school.') }}<br/>
            Generated on {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>
</body>
</html>
