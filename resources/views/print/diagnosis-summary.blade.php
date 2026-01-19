<x-print.layout title="Rekap Riwayat Diagnosis - SP Ayam Broiler">
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
            Tanggal Cetak:<br>
            <strong>{{ now()->format('d F Y') }}</strong>
        </div>
    </div>

    {{-- Title --}}
    <div class="report-title">
        REKAP RIWAYAT DIAGNOSIS
        @if(request('from') || request('to'))
            <br><small style="font-size: 9pt; font-weight: normal; color: #64748b;">
                Periode: {{ request('from', 'Awal') }} - {{ request('to', 'Sekarang') }}
            </small>
        @endif
    </div>

    {{-- Summary Stats --}}
    <div style="display: flex; gap: 15px; margin-bottom: 20px;">
        <div
            style="flex: 1; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 12px; text-align: center;">
            <div style="font-size: 20pt; font-weight: 700; color: #16a34a;">{{ $riwayatList->count() }}</div>
            <div style="font-size: 8pt; color: #15803d;">Total Diagnosis</div>
        </div>
        <div
            style="flex: 1; background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 12px; text-align: center;">
            <div style="font-size: 20pt; font-weight: 700; color: #dc2626;">
                {{ $riwayatList->unique('id_penyakit')->count() }}</div>
            <div style="font-size: 8pt; color: #b91c1c;">Jenis Penyakit</div>
        </div>
        <div
            style="flex: 1; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 12px; text-align: center;">
            <div style="font-size: 20pt; font-weight: 700; color: #2563eb;">
                {{ $riwayatList->sum(fn($r) => $r->gejala->count()) }}</div>
            <div style="font-size: 8pt; color: #1d4ed8;">Total Gejala</div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="section-title">Daftar Riwayat Diagnosis</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Hasil Diagnosis</th>
                <th style="width: 50px;" class="text-center">Gejala</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatList as $index => $riwayat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $riwayat->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $riwayat->nama }}</td>
                    <td>{{ $riwayat->telepon ?? '-' }}</td>
                    <td>
                        @if($riwayat->penyakit)
                            <span class="badge badge-danger">{{ $riwayat->penyakit->nama_penyakit }}</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $riwayat->gejala->count() }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 30px; color: #94a3b8;">
                        Tidak ada data riwayat diagnosis
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="report-footer">
        <div class="footer-info">
            Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB<br>
            SP Ayam Broiler - Sistem Pakar Diagnosis Penyakit<br>
            Total data: {{ $riwayatList->count() }} record
        </div>
        <div class="signature-box">
            <div class="sig-title">Mengetahui,</div>
            <div class="sig-line">Petugas</div>
        </div>
    </div>
</x-print.layout>
