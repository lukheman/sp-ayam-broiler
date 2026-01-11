<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Manajemen Gejala" subtitle="Kelola data gejala penyakit ayam broiler">
        <x-slot:actions>
            <x-admin.button variant="primary" icon="fas fa-plus" wire:click="openCreateModal">
                Tambah Gejala
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

    {{-- Gejala Table Card --}}
    <div class="modern-card">
        {{-- Search and Filters --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">Daftar Gejala</h5>
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: var(--input-bg); border-color: var(--border-color);">
                    <i class="fas fa-search" style="color: var(--text-muted);"></i>
                </span>
                <input type="text" class="form-control" placeholder="Cari gejala..."
                    wire:model.live.debounce.300ms="search" style="border-left: none;">
            </div>
        </div>

        {{-- Gejala Table --}}
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width: 100px;">Kode</th>
                        <th>Nama Gejala</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gejalaList as $gejala)
                        <tr wire:key="gejala-{{ $gejala->id }}">
                            <td>
                                <x-admin.badge variant="primary">{{ $gejala->kode_gejala }}</x-admin.badge>
                            </td>
                            <td style="color: var(--text-primary);">{{ $gejala->nama_gejala }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="action-btn action-btn-edit" wire:click="openEditModal({{ $gejala->id }})"
                                        title="Edit gejala">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete"
                                        wire:click="confirmDelete({{ $gejala->id }})" title="Hapus gejala">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-clipboard-list mb-2" style="font-size: 2rem;"></i>
                                    <p class="mb-0">Belum ada data gejala</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($gejalaList->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $gejalaList->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="modal-backdrop-custom" wire:click.self="closeModal">
            <div class="modal-content-custom" wire:click.stop>
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom">
                        {{ $editingId ? 'Edit Gejala' : 'Tambah Gejala Baru' }}
                    </h5>
                    <button type="button" class="modal-close-btn" wire:click="closeModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="mb-3">
                        <label for="kode_gejala" class="form-label">Kode Gejala <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('kode_gejala') is-invalid @enderror" id="kode_gejala"
                            wire:model="kode_gejala" placeholder="Contoh: G01">
                        @error('kode_gejala')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nama_gejala" class="form-label">Nama Gejala <span
                                style="color: var(--danger-color);">*</span></label>
                        <input type="text" class="form-control @error('nama_gejala') is-invalid @enderror" id="nama_gejala"
                            wire:model="nama_gejala" placeholder="Masukkan nama gejala">
                        @error('nama_gejala')
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
        message="Apakah Anda yakin ingin menghapus gejala ini? Tindakan ini tidak dapat dibatalkan." on-confirm="delete"
        on-cancel="cancelDelete" variant="danger" icon="fas fa-exclamation-triangle">
        <x-slot:confirmButton>
            <i class="fas fa-trash-alt me-2"></i>Hapus Gejala
        </x-slot:confirmButton>
    </x-admin.confirm-modal>
</div>