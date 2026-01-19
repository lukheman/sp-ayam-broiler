<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laporan - SP Ayam Broiler' }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }

        .page {
            max-width: 210mm;
            margin: 0 auto;
            padding: 5mm;
        }

        /* Header */
        .report-header {
            display: flex;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
        }

        .logo-area {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .logo-area span {
            font-size: 28px;
        }

        .header-text {
            flex: 1;
        }

        .header-text h1 {
            font-size: 16pt;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header-text p {
            font-size: 9pt;
            color: #64748b;
        }

        .header-date {
            text-align: right;
            font-size: 9pt;
            color: #64748b;
        }

        /* Report Title */
        .report-title {
            text-align: center;
            font-size: 13pt;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 6px;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            display: flex;
        }

        .info-label {
            width: 100px;
            font-weight: 600;
            color: #475569;
            flex-shrink: 0;
        }

        .info-separator {
            width: 15px;
            text-align: center;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            color: #1e293b;
        }

        /* Section Title */
        .section-title {
            font-size: 10pt;
            font-weight: 700;
            color: #1e293b;
            padding: 8px 12px;
            background: #f1f5f9;
            border-left: 3px solid #6366f1;
            margin: 15px 0 10px;
        }

        /* Result Box */
        .result-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .disease-name {
            font-size: 12pt;
            font-weight: 700;
            color: #dc2626;
            margin-bottom: 6px;
        }

        .solution-text {
            font-size: 9pt;
            color: #64748b;
            line-height: 1.6;
        }

        .solution-text strong {
            color: #475569;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: left;
        }

        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            font-size: 9pt;
        }

        .data-table td {
            font-size: 9pt;
            color: #1e293b;
        }

        .data-table tr:nth-child(even) {
            background: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        /* Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .footer-info {
            font-size: 8pt;
            color: #94a3b8;
        }

        .signature-box {
            text-align: center;
            width: 150px;
        }

        .signature-box .sig-title {
            font-size: 9pt;
            color: #64748b;
            margin-bottom: 50px;
        }

        .signature-box .sig-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 9pt;
            font-weight: 600;
            color: #1e293b;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: 600;
        }

        .badge-danger {
            background: #fef2f2;
            color: #dc2626;
        }

        .badge-info {
            background: #eff6ff;
            color: #2563eb;
        }

        /* Print specific */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .page {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Screen only */
        @media screen {
            body {
                background: #f1f5f9;
                padding: 20px;
            }

            .page {
                background: white;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                padding: 20mm 15mm;
            }

            .print-btn {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #6366f1;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
                transition: all 0.2s;
            }

            .print-btn:hover {
                background: #4f46e5;
                transform: translateY(-2px);
            }

            .back-btn {
                position: fixed;
                top: 20px;
                left: 20px;
                background: #64748b;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                transition: all 0.2s;
            }

            .back-btn:hover {
                background: #475569;
                transform: translateY(-2px);
            }
        }
    </style>
</head>

<body>
    <a href="{{ url()->previous() }}" class="back-btn no-print">
        ← Kembali
    </a>
    <button onclick="window.print()" class="print-btn no-print">
        🖨️ Cetak Laporan
    </button>

    <div class="page">
        {{ $slot }}
    </div>
</body>

</html>