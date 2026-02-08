<x-print.layout title="Laporan Diagnosis - {{ $riwayat->nama }}">
    {{-- Header --}}
    <div class="report-header">
        <div class="logo-area">
            <span>🐔</span>
        </div>
        <div class="header-text">
            <h1>SP AYAM BROILER</h1>
            <p>Sistem Pakar Diagnosis Penyakit Ayam Broiler</p>
        </div>
        <div class="header-date">
            <strong>No. {{ str_pad($riwayat->id, 5, '0', STR_PAD_LEFT) }}</strong><br>
            {{ $riwayat->tanggal->format('d F Y') }}
        </div>
    </div>

    {{-- Title --}}
    <div class="report-title">
        LAPORAN HASIL DIAGNOSIS
    </div>

    {{-- Patient Info --}}
    <div class="info-section">
        <div class="section-title">Data Pasien</div>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $riwayat->nama }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $riwayat->tanggal->format('d F Y') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Alamat</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $riwayat->alamat ?? '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Telepon</span>
                <span class="info-separator">:</span>
                <span class="info-value">{{ $riwayat->telepon ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Diagnosis Result --}}
    <div class="section-title">Hasil Diagnosis</div>
    @if($riwayat->penyakit)
        <div class="result-box">
            <div class="disease-name">
                {{ $riwayat->penyakit->kode_penyakit }} - {{ $riwayat->penyakit->nama_penyakit }}
            </div>
            @if($calculationSteps)
                <div class="probability-text">
                    <strong>Probabilitas:</strong> {{ $calculationSteps['total_bayes_percentage'] }}%
                </div>
            @endif
            @if($riwayat->penyakit->solusi)
                <div class="solution-text">
                    <strong>Solusi:</strong> {{ $riwayat->penyakit->solusi }}
                </div>
            @endif
        </div>
    @else
        <p style="color: #64748b; padding: 10px 0;">Tidak ada hasil diagnosis</p>
    @endif

    {{-- Symptoms --}}
    <div class="section-title">Gejala yang Dialami ({{ $riwayat->gejala->count() }} gejala)</div>
    @if($riwayat->gejala->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">No</th>
                    <th style="width: 80px;">Kode</th>
                    <th>Nama Gejala</th>
                </tr>
            </thead>
            <tbody>
                @foreach($riwayat->gejala as $index => $gejala)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $gejala->kode_gejala }}</td>
                        <td>{{ $gejala->nama_gejala }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #64748b; padding: 10px 0;">Tidak ada gejala tercatat</p>
    @endif

    {{-- Calculation Process --}}
    @if($calculationSteps && isset($calculationSteps['steps']))
        <div class="section-title">Proses Perhitungan Teorema Bayes</div>

        @if($calculationSummary)
            <div class="calc-info-box">
                <strong>Metode:</strong> Teorema Bayes |
                <strong>Formula:</strong> <code>{{ $calculationSummary['formula_bayes'] }}</code>
            </div>
        @endif

        @foreach($calculationSteps['steps'] as $step)
            <div class="calc-step">
                <div class="calc-step-title">
                    Tahap {{ $step['step'] }}: {{ $step['title'] }}
                </div>
                <div class="calc-step-content">
                    <p><strong>Deskripsi:</strong> {{ $step['description'] }}</p>
                    <p><strong>Formula:</strong> <code>{{ $step['formula'] }}</code></p>

                    @if(isset($step['details']) && is_array($step['details']) && count($step['details']) > 0)
                        @if($step['step'] == 1)
                            <div class="detail-items">
                                @foreach($step['details'] as $detail)
                                    <span class="detail-badge">{{ $detail }}</span>
                                @endforeach
                            </div>
                        @else
                            <table class="calc-table">
                                <thead>
                                    <tr>
                                        <th>Gejala</th>
                                        <th>Perhitungan</th>
                                        <th>Hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($step['details'] as $detail)
                                        <tr>
                                            <td><code>{{ $detail['gejala'] }}</code></td>
                                            <td>{{ $detail['calculation'] }}</td>
                                            <td><strong>{{ $detail['result'] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif

                    @if(isset($step['calculation']))
                        <p><strong>Perhitungan:</strong> {{ $step['calculation'] }}</p>
                    @endif

                    @if(isset($step['result']) && $step['result'] !== null)
                        <div class="calc-result">
                            <strong>Hasil:</strong> {{ $step['result'] }}
                            @if(isset($step['percentage']))
                                <span class="percentage-badge">{{ $step['percentage'] }}%</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        {{-- Conclusion --}}
        <div class="calc-conclusion">
            <strong>Kesimpulan untuk {{ $calculationSteps['penyakit_kode'] }}:</strong><br>
            Nilai Bayes: <strong>{{ round($calculationSteps['total_bayes'], 6) }}</strong> |
            Persentase: <strong>{{ $calculationSteps['total_bayes_percentage'] }}%</strong>
        </div>
    @endif

    {{-- Footer --}}
    <div class="report-footer">
        <div class="footer-info">
            Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB<br>
            SP Ayam Broiler - Sistem Pakar Diagnosis Penyakit
        </div>
        <div class="signature-box">
            <div class="sig-title">Mengetahui,</div>
            <div class="sig-line">Petugas</div>
        </div>
    </div>

    <style>
        .probability-text {
            font-size: 10pt;
            color: #059669;
            margin-bottom: 6px;
        }

        .calc-info-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 9pt;
        }

        .calc-info-box code {
            background: #dcfce7;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        .calc-step {
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        .calc-step-title {
            background: #f1f5f9;
            padding: 8px 12px;
            font-weight: 600;
            font-size: 9pt;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }

        .calc-step-content {
            padding: 10px 12px;
            font-size: 9pt;
        }

        .calc-step-content p {
            margin: 5px 0;
        }

        .calc-step-content code {
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
            font-size: 8pt;
        }

        .detail-items {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 8px 0;
        }

        .detail-badge {
            background: #eef2ff;
            color: #4f46e5;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
        }

        .calc-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 8pt;
        }

        .calc-table th,
        .calc-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            text-align: left;
        }

        .calc-table th {
            background: #f8fafc;
            font-weight: 600;
        }

        .calc-result {
            background: #fef3c7;
            padding: 8px 12px;
            border-radius: 4px;
            margin-top: 8px;
        }

        .percentage-badge {
            background: #10b981;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            margin-left: 8px;
        }

        .calc-conclusion {
            background: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 6px;
            padding: 12px 15px;
            margin-top: 15px;
            font-size: 9pt;
        }

        @media print {
            .calc-step {
                break-inside: avoid;
            }
        }
    </style>
</x-print.layout>