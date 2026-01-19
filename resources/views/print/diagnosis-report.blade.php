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
</x-print.layout>