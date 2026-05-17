<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Skrining - {{ $patient->name }}</title>
    <link rel="icon" type="image/png" href="{{ public_path('images/logo-podiatrix.png') }}">
    <link rel="shortcut icon" href="{{ public_path('images/logo-podiatrix.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.4;
        }

        /* Header Styles */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            padding-bottom: 20px;
            border-bottom: 3px solid #058a84;
        }

        .logo {
            display: table-cell;
            width: 120px;
            height: 120px;
            vertical-align: middle;
            padding-right: 4px;
        }

        .logo img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 100%;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #058a84;
            margin: 0;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0 0 0;
        }

        /* Date Section */
        .date-section {
            text-align: center;
            margin-bottom: 25px;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        /* Two Column Layout */
        .two-column-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .two-column-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .two-column-table .label {
            font-weight: bold;
            color: #4b5563;
            width: 19%;
            background-color: #f9fafb;
            padding-right: 2px;
        }

        .two-column-table .colon {
            width: 1%;
            background-color: #f9fafb;
            font-weight: bold;
            color: #4b5563;
            text-align: right;
            padding-left: 2px;
            padding-right: 10px;
        }

        .two-column-table .value {
            color: #1f2937;
            width: 30%;
        }

        /* Section Styles */
        .content {
            margin-top: 20px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #058a84;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Keep all main report content on first page */
        .first-page-content {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Keep main content on first page */
        .main-content {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Keep recommendations and notes together */
        .recommendations-notes-container {
            page-break-inside: avoid;
            page-break-before: auto;
        }

        /* Table Styles */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 180px;
            color: #4b5563;
        }

        .info-table td:last-child {
            color: #1f2937;
        }

        /* Status Badge Styles */
        .risk-classification {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            min-width: 100px;
        }

        .risk-very-low {
            background-color: #d1fae5;
            color: #058a84;
        }

        .risk-low {
            background-color: #fef3c7;
            color: #92400e;
        }

        .risk-medium {
            background-color: #fed7aa;
            color: #9a3412;
        }

        .risk-high {
            background-color: #fecaca;
            color: #991b1b;
        }

        .risk-emergency {
            background-color: #fecaca;
            color: #7f1d1d;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-normal {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-prediabetes {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-diabetes {
            background-color: #fecaca;
            color: #991b1b;
        }

        /* Notes Styles */
        .notes-box {
            background-color: #f8fafc;
            padding: 15px;
            border-left: 4px solid #058a84;
            border-radius: 6px;
            margin-top: 10px;
        }

        .notes-text {
            margin: 0;
            line-height: 1.6;
            white-space: pre-wrap;
            color: #374151;
        }

        /* Recommendations Styles */
        .recommendation-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .recommendation-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
            padding: 8px;
            background-color: #f9fafb;
            border-radius: 6px;
        }

        .recommendation-bullet {
            color: #10b981;
            font-weight: bold;
            margin-right: 10px;
            font-size: 16px;
        }

        .recommendation-text {
            flex: 1;
            font-size: 13px;
            line-height: 1.5;
            color: #374151;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }

        /* Keep education content on one page */
        .education-content {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Education Page Styles */
        .education-header {
            text-align: center;
            background-color: #058a84;
            color: white;
            padding: 15px;
            margin: -20px -20px 20px -20px;
            border-radius: 0;
        }

        .education-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .education-subtitle {
            font-size: 12px;
            margin: 4px 0 0 0;
            opacity: 0.9;
        }

        .pillar {
            margin-bottom: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            border-left: 4px solid #058a84;
        }

        .pillar-title {
            font-size: 14px;
            font-weight: bold;
            color: #058a84;
            margin-bottom: 6px;
        }

        .pillar-quote {
            font-style: italic;
            color: #6b7280;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .pillar-content {
            font-size: 11px;
            line-height: 1.4;
        }

        .info-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 4px;
            font-size: 11px;
        }

        .bullet-point {
            margin-left: 15px;
            margin-bottom: 2px;
            font-size: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #4b5563;
            border-top: 2px solid #058a84;
            padding-top: 16px;
        }

        .footer p {
            margin: 4px 0;
            line-height: 1.5;
        }

        .footer .method-info {
            color: #058a84;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Page 1: Main Report -->
    <div class="first-page-content">
        <div class="header">
            <div class="logo">
                <img src="{{ public_path('images/logo-podiatrix.png') }}" alt="Podiatrix Logo">
            </div>
            <div class="header-text">
                <h1 class="title">LAPORAN HASIL SKRINING</h1>
                <p class="subtitle">Skrining Kaki Diabetik Berdasarkan Metode Inlow's 60-Second Diabetic Foot Screen</p>
            </div>
        </div>

        <!-- Screening Date -->
        <div class="date-section">
            <strong>Tanggal Skrining:</strong> {{ $screening->created_at->locale('id')->translatedFormat('d F Y') }}
        </div>

        <div class="content main-content">
            <!-- 1. Patient Identity -->
            <div class="section">
                <div class="section-title">Identitas Pasien</div>
                <table class="two-column-table">
                    <tr>
                        <td class="label">Nama Lengkap</td><td class="colon">:</td>
                        <td class="value">{{ $patient->name }}</td>
                        <td class="label">Jenis Kelamin</td><td class="colon">:</td>
                        <td class="value">
                            {{ $patient->gender === 'Male' ? 'Laki-laki' : ($patient->gender === 'Female' ? 'Perempuan' : $patient->gender) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Usia</td><td class="colon">:</td>
                        <td class="value">{{ $patient->age }} tahun</td>
                        <td class="label">Tanggal Lahir</td><td class="colon">:</td>
                        <td class="value">
                            {{ \Carbon\Carbon::parse($patient->date_of_birth)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Pendidikan Terakhir</td><td class="colon">:</td>
                        <td class="value">{{ $patient->last_education }}</td>
                        <td class="label">Pekerjaan</td><td class="colon">:</td>
                        <td class="value">{{ $patient->occupation }}</td>
                    </tr>
                </table>
            </div>

            <!-- 2. Screening Results -->
            <div class="section">
                <div class="section-title">Hasil Skrining</div>
                <table class="two-column-table bg-white">
                    <tr>
                        <td class="label">Klasifikasi Risiko</td><td class="colon">:</td>
                        <td class="value" colspan="4">
                            <span
                                class="risk-classification
                                @if ($screening->risk_classification === 'Sangat Rendah') risk-very-low
                                @elseif($screening->risk_classification === 'Rendah') risk-low
                                @elseif($screening->risk_classification === 'Sedang') risk-medium
                                @elseif($screening->risk_classification === 'Tinggi') risk-high
                                @elseif($screening->risk_classification === 'Darurat') risk-emergency
                                @else risk-medium @endif">
                                {{ $screening->risk_classification }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Recommendations and Notes Container -->
            <div class="recommendations-notes-container">
                <!-- 3. Treatment Recommendations -->
                <div class="section">
                    <div class="section-title">Rekomendasi Tindakan</div>
                    @if (!empty($screening->recommendation) && is_array($screening->recommendation))
                        <ul class="recommendation-list">
                            @foreach ($screening->recommendation as $index => $recommendation)
                                <li class="recommendation-item">
                                    <span class="recommendation-bullet">{{ $index + 1 }}.</span>
                                    <span class="recommendation-text">{{ $recommendation }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="notes-box">
                            <p class="notes-text">Belum ada rekomendasi tindakan yang ditetapkan.</p>
                        </div>
                    @endif
                </div>

                <!-- 4. Clinical Notes -->
                @if (!empty(trim($screening->notes)))
                    <div class="section">
                        <div class="section-title">Catatan Klinis</div>
                        <div class="notes-box">
                            <p class="notes-text">{{ $screening->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer Information -->
    <div class="footer">
        <p class="method-info">Metode skrining yang digunakan pada laporan ini didasarkan pada instrumen pemeriksaan <strong>Inlow's 60-Second Diabetic Foot Screen</strong>.</p>
        <p>Copyright &copy; {{ date('Y') }} Podiatrix. All rights reserved.</p>
    </div>

</body>

</html>
