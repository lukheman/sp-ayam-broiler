<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Riwayat Diagnosis" subtitle="Lihat riwayat diagnosis penyakit ayam broiler">
        <x-slot:actions>
            <a href="{{ route('admin.print.diagnosis-summary') }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-print me-2"></i>Cetak Semua
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert variant="success" title="Berhasil!" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    @if (session('error'))
        <x-admin.alert variant="danger" title="Error!" class="mb-4">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    {{-- Riwayat Table Card --}}
    <div class="modern-card">
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Riwayat Diagnosis</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari berdasarkan nama atau penyakit..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Riwayat Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 120px;">Tanggal</th>
                        <th>Nama</th>
                        <th>No Telepon</th>
                        <th>Hasil Diagnosis</th>
                        <th>Jumlah Gejala</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($riwayatList as $riwayat)
                        <tr wire:key="riwayat-{{ $riwayat->id }}">
                            <td style="color: var(--text-primary);">
                                {{ $riwayat->tanggal->format('d/m/Y') }}
                            </td>
                            <td style="color: var(--text-primary);">{{ $riwayat->nama }}</td>
                            <td style="color: var(--text-primary);">{{ $riwayat->telepon ?? '-' }}</td>
                            <td>
                                @if ($riwayat->penyakit)
                                    <x-admin.badge variant="danger">{{ $riwayat->penyakit->nama_penyakit }}</x-admin.badge>
                                @else
                                    <x-admin.badge variant="secondary">Tidak ada hasil</x-admin.badge>
                                @endif
                            </td>
                            <td>
                                <x-admin.badge variant="info">{{ $riwayat->gejala->count() }} gejala</x-admin.badge>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-view"
                                        wire:click="openDetailModal({{ $riwayat->id }})" title="Lihat detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.print.diagnosis', $riwayat->id) }}" target="_blank"
                                        class="action-btn action-btn-print" title="Cetak laporan">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $riwayat->id }})" title="Hapus riwayat">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-history mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada riwayat diagnosis</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($riwayatList->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $riwayatList->links() }}
            </div>
        @endif
    </div>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $selectedRiwayat)
        <div class="modal-backdrop-custom" wire:click.self="closeDetailModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 600px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        Detail Riwayat Diagnosis
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeDetailModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    {{-- Info Diagnosis --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Tanggal</label>
                            <p class="mb-0" style="color: var(--text-primary); font-weight: 500;">
                                {{ $selectedRiwayat->tanggal->format('d F Y') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Nama</label>
                            <p class="mb-0" style="color: var(--text-primary); font-weight: 500;">
                                {{ $selectedRiwayat->nama }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Alamat</label>
                            <p class="mb-0" style="color: var(--text-primary); font-weight: 500;">
                                {{ $selectedRiwayat->alamat ?? '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Telepon</label>
                            <p class="mb-0" style="color: var(--text-primary); font-weight: 500;">
                                {{ $selectedRiwayat->telepon ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Hasil Diagnosis --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Hasil Diagnosis</label>
                        @if ($selectedRiwayat->penyakit)
                            <div class="p-3 rounded"
                                style="background: rgba(var(--danger-rgb), 0.1); border: 1px solid rgba(var(--danger-rgb), 0.2);">
                                <h6 class="mb-1" style="color: var(--danger-color); font-weight: 600;">
                                    {{ $selectedRiwayat->penyakit->kode_penyakit }} -
                                    {{ $selectedRiwayat->penyakit->nama_penyakit }}
                                </h6>
                                @if ($selectedRiwayat->penyakit->solusi)
                                    <p class="mb-0 small" style="color: var(--text-secondary);">
                                        <strong>Solusi:</strong> {{ $selectedRiwayat->penyakit->solusi }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="mb-0 text-muted">Tidak ada hasil diagnosis</p>
                        @endif
                    </div>

                    {{-- Gejala yang Dialami --}}
                    <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Gejala yang Dialami</label>
                        @if ($selectedRiwayat->gejala->count() > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($selectedRiwayat->gejala as $gejala)
                                    <span class="badge"
                                        style="background: var(--primary-color); color: white; font-weight: 500; padding: 6px 12px;">
                                        {{ $gejala->kode_gejala }} - {{ $gejala->nama_gejala }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="mb-0 text-muted">Tidak ada gejala tercatat</p>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.print.diagnosis', $selectedRiwayat->id) }}" target="_blank"
                        class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i>Cetak
                    </a>
                    <x-admin.button type="button" variant="outline" wire:click="closeDetailModal">
                        Tutup
                    </x-admin.button>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus riwayat diagnosis ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Riwayat
        </x-slot:confirmButton>
    </x-admin.confirm-modal>

    {{-- Print Styles --}}
    <style>
        .action-btn-print {
            color: var(--primary-color);
            text-decoration: none;
        }

        .action-btn-print:hover {
            color: var(--primary-dark);
        }
    </style>
</div>