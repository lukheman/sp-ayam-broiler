<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Basis Pengetahuan" subtitle="Kelola relasi penyakit dengan gejala ayam broiler">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Relasi
            </x-admin.button>
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

    {{-- Basis Pengetahuan Table Card --}}
    <div class="modern-card">
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Basis Pengetahuan</h5>
            <div class="d-flex gap-2 flex-wrap">
                <div class="input-group" style="max-width: 250px;">
                    <span class="input-group-text"
                        style="background: var(--input-bg); border-color: var(--border-color);">
                        <i class="fas fa-search" style="color: var(--text-muted);"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Cari penyakit..."
                        wire:model.live.debounce.300ms="search" style="border-left: none;">
                </div>
            </div>
        </div>

        {{-- Basis Pengetahuan Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 100px;">Kode</th>
                        <th>Nama Penyakit</th>
                        <th>Gejala Terkait</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($basisPengetahuan as $penyakit)
                        <tr wire:key="basis-{{ $penyakit->id }}">
                            <td>
                                <x-admin.badge variant="secondary">{{ $penyakit->kode_penyakit }}</x-admin.badge>
                            </td>
                            <td style="color: var(--text-primary);">{{ $penyakit->nama_penyakit }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($penyakit->gejala->take(5) as $gejala)
                                        <span class="badge-gejala"
                                            title="{{ $gejala->nama_gejala }} (Bobot: {{ $gejala->pivot->bobot }})">
                                            {{ $gejala->kode_gejala }}
                                        </span>
                                    @endforeach
                                    @if($penyakit->gejala->count() > 5)
                                        <span class="badge-more">+{{ $penyakit->gejala->count() - 5 }} lainnya</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $penyakit->gejala_count }} gejala</small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-view" wire:click="showDetail({{ $penyakit->id }})"
                                        title="Lihat detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn action-btn-edit"
                                        wire:click="openEditModal({{ $penyakit->id }})" title="Edit relasi">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $penyakit->id }})" title="Hapus relasi">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-brain mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data basis pengetahuan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($basisPengetahuan->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $basisPengetahuan->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 600px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Basis Pengetahuan' : 'Tambah Basis Pengetahuan' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="id_penyakit" class="form-label">Penyakit <span
                                style="color: var(--danger-color);">*</span></label>
                        <select class="form-control @error('id_penyakit') is-invalid @enderror" id="id_penyakit"
                            wire:model="id_penyakit" {{ $editingId ? 'disabled' : '' }}>
                            <option value="">-- Pilih Penyakit --</option>
                            @foreach($penyakitList as $penyakit)
                                <option value="{{ $penyakit->id }}">{{ $penyakit->kode_penyakit }} -
                                    {{ $penyakit->nama_penyakit }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_penyakit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Gejala Terkait <span style="color: var(--danger-color);">*</span></label>
                        <p class="text-muted small mb-2">Pilih gejala yang berhubungan dengan penyakit ini</p>

                        @error('selected_gejala')
                            <div class="alert alert-danger py-2 mb-2">{{ $message }}</div>
                        @enderror

                        <div class="gejala-checkbox-container">
                            @foreach($gejalaList as $gejala)
                                <div
                                    class="gejala-checkbox-item {{ in_array($gejala->id, $selected_gejala) ? 'selected' : '' }}">
                                    <div class="gejala-main" wire:click="toggleGejala({{ $gejala->id }})"
                                        style="cursor: pointer; display: flex; align-items: center; gap: 0.75rem; flex: 1;">
                                        <span class="gejala-code">{{ $gejala->kode_gejala }}</span>
                                        <span class="gejala-name">{{ $gejala->nama_gejala }}</span>
                                        <span class="gejala-check">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    </div>
                                    @if(in_array($gejala->id, $selected_gejala))
                                        <div class="bobot-input-wrapper" wire:click.stop>
                                            <label class="bobot-label">Bobot:</label>
                                            <input type="number" class="form-control form-control-sm bobot-input"
                                                wire:model="bobot_gejala.{{ $gejala->id }}" step="0.01" min="0" max="1"
                                                placeholder="0.00">
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-2 text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ count($selected_gejala) }} gejala dipilih
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <x-admin.button type="button" variant="outline" wire:click="closeModal">
                            Batal
                        </x-admin.button>
                        <x-admin.button type="submit" variant="primary">
                            {{ $editingId ? 'Perbarui' : 'Simpan' }}
                        </x-admin.button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-admin.confirm-modal :show="$showDeleteModal" title="Konfirmasi Hapus"
        message="Apakah Anda yakin ingin menghapus semua relasi gejala untuk penyakit ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Relasi
        </x-slot:confirmButton>
    </x-admin.confirm-modal>

    {{-- Detail Modal --}}
    @if ($showDetailModal && $detailPenyakit)
        <div class="modal-backdrop-custom" wire:click.self="closeDetailModal">
            <div class="modal-content-custom" wire:click.stop style="max-width: 650px;">
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>
                        Detail Basis Pengetahuan
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeDetailModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="detail-content">
                    {{-- Penyakit Info --}}
                    <div class="detail-section mb-4">
                        <h6 class="detail-section-title">Informasi Penyakit</h6>
                        <div class="detail-card">
                            <div class="d-flex align-items-center gap-3">
                                <span class="detail-code">{{ $detailPenyakit->kode_penyakit }}</span>
                                <div>
                                    <h5 class="mb-0" style="color: var(--text-primary);">
                                        {{ $detailPenyakit->nama_penyakit }}
                                    </h5>
                                    <small class="text-muted">{{ $detailPenyakit->gejala->count() }} gejala terkait</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Gejala List --}}
                    <div class="detail-section">
                        <h6 class="detail-section-title">Daftar Gejala</h6>
                        <div class="gejala-list">
                            @forelse($detailPenyakit->gejala as $gejala)
                                <div class="gejala-item">
                                    <span class="gejala-code">{{ $gejala->kode_gejala }}</span>
                                    <span class="gejala-name">{{ $gejala->nama_gejala }}</span>
                                    <span class="gejala-bobot">Bobot: {{ $gejala->pivot->bobot }}</span>
                                </div>
                            @empty
                                <div class="text-muted text-center py-3">
                                    <i class="fas fa-inbox mb-2" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0">Tidak ada gejala terkait</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <x-admin.button type="button" variant="outline" wire:click="closeDetailModal">
                        Tutup
                    </x-admin.button>
                    <x-admin.button type="button" variant="primary" wire:click="editFromDetail({{ $detailPenyakit->id }})">
                        <i class="fas fa-edit me-1"></i> Edit
                    </x-admin.button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .badge-gejala {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-more {
            background: var(--hover-bg);
            color: var(--text-secondary);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .gejala-checkbox-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem;
        }

        .gejala-checkbox-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: var(--bg-tertiary);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .gejala-checkbox-item:hover {
            border-color: var(--primary-color);
            background: var(--hover-bg);
        }

        .gejala-checkbox-item.selected {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.1);
        }

        .gejala-code {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 40px;
            text-align: center;
        }

        .gejala-name {
            flex: 1;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .gejala-check {
            width: 24px;
            height: 24px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: transparent;
            transition: all 0.2s ease;
        }

        .gejala-checkbox-item.selected .gejala-check {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .action-btn-view {
            color: var(--secondary-color);
        }

        .action-btn-view:hover {
            background: rgba(14, 165, 233, 0.1);
        }

        .detail-section-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }

        .detail-card {
            background: var(--bg-tertiary);
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid var(--border-color);
        }

        .detail-code {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
        }

        .gejala-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem;
        }

        .gejala-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: var(--bg-tertiary);
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .gejala-item:last-child {
            margin-bottom: 0;
        }

        .gejala-item:hover {
            background: var(--hover-bg);
        }

        .bobot-input-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
            padding-left: 1rem;
        }

        .bobot-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .bobot-input {
            width: 70px;
            text-align: center;
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }

        .gejala-bobot {
            margin-left: auto;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</div>