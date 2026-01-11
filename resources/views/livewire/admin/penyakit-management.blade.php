<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Penyakit" subtitle="Kelola data penyakit ayam broiler">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Penyakit
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

    {{-- Penyakit Table Card --}}
    <div class="modern-card">
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Penyakit</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari penyakit..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Penyakit Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 100px;">Kode</th>
                        <th>Nama Penyakit</th>
                        <th style="width: 150px;">Jumlah Gejala</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penyakitList as $penyakit)
                        <tr wire:key="penyakit-{{ $penyakit->id }}">
                            <td>
                                <x-admin.badge variant="secondary">{{ $penyakit->kode_penyakit }}</x-admin.badge>
                            </td>
                            <td style="color: var(--text-primary);">{{ $penyakit->nama_penyakit }}</td>
                            <td>
                                <span class="text-muted">{{ $penyakit->gejala_count ?? $penyakit->gejala()->count() }}
                                    gejala</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-edit"
                                        wire:click="openEditModal({{ $penyakit->id }})" title="Edit penyakit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $penyakit->id }})" title="Hapus penyakit">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-virus mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data penyakit</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($penyakitList->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $penyakitList->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Penyakit' : 'Tambah Penyakit Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="kode_penyakit" class="form-label">Kode Penyakit <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('kode_penyakit') is-invalid @enderror"
                            id="kode_penyakit" wire:model="kode_penyakit" placeholder="Contoh: P01">
                        @error('kode_penyakit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nama_penyakit" class="form-label">Nama Penyakit <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama_penyakit') is-invalid @enderror"
                            id="nama_penyakit" wire:model="nama_penyakit" placeholder="Masukkan nama penyakit">
                        @error('nama_penyakit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
        message="Apakah Anda yakin ingin menghapus penyakit ini? Tindakan ini tidak dapat dibatalkan."
        on-confirm="delete" on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Penyakit
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>