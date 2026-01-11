<div>
    <x-admin.page-header title="Profil Pengguna" subtitle="Kelola informasi profil dan password Anda">
    </x-admin.page-header>

    {{-- Success Alert --}}
    @if (session('success'))
        <x-admin.alert variant="success" class="mb-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    <div class="row g-4">
        {{-- Profile Information Card --}}
        <div class="col-lg-4">
            <x-admin.modern-card>
                <div class="text-center p-4">
                    {{-- Profile Photo --}}
                    <div class="photo-upload-container mx-auto mb-3">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="profile-photo">
                        @elseif($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" alt="{{ $user->nama }}" class="profile-photo">
                        @else
                            <div class="profile-avatar">
                                {{ $user->initials() }}
                            </div>
                        @endif
                        <label for="photo-input" class="photo-upload-btn" title="Ganti Foto">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="photo-input" wire:model="photo" accept="image/*" class="d-none">
                    </div>

                    {{-- Photo Upload Actions --}}
                    @if($photo)
                        <div class="d-flex gap-2 justify-content-center mb-3">
                            <button type="button" class="btn btn-sm btn-primary" wire:click="updatePhoto">
                                <span wire:loading.remove wire:target="updatePhoto">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </span>
                                <span wire:loading wire:target="updatePhoto">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="$set('photo', null)">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                        </div>
                    @elseif($user->photo)
                        <button type="button" class="btn btn-sm btn-outline-danger mb-3" wire:click="deletePhoto"
                            wire:confirm="Apakah Anda yakin ingin menghapus foto profil?">
                            <i class="fas fa-trash me-1"></i> Hapus Foto
                        </button>
                    @endif

                    @error('photo')
                        <div class="text-danger small mb-3">{{ $message }}</div>
                    @enderror

                    <div wire:loading wire:target="photo" class="text-muted small mb-3">
                        <i class="fas fa-spinner fa-spin me-1"></i> Mengunggah...
                    </div>

                    <h4 class="mb-1" style="color: var(--text-primary);">{{ $user->nama }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <x-admin.badge variant="primary" icon="fas fa-user-shield">Administrator</x-admin.badge>

                </div>
            </x-admin.modern-card>
        </div>

        {{-- Edit Profile Card --}}
        <div class="col-lg-8">
            <x-admin.modern-card>
                <div class="p-4">
                    <h5 class="mb-4" style="color: var(--text-primary); font-weight: 600;">
                        <i class="fas fa-user-edit me-2" style="color: var(--primary-color);"></i>
                        Edit Profil
                    </h5>

                    <form wire:submit="updateProfile">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    wire:model="nama" placeholder="Masukkan nama lengkap">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    wire:model="email" placeholder="Masukkan email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-admin.button type="submit" variant="primary" icon="fas fa-save">
                                <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                                <span wire:loading wire:target="updateProfile">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...
                                </span>
                            </x-admin.button>
                        </div>
                    </form>
                </div>
            </x-admin.modern-card>

            {{-- Change Password Card --}}
            <x-admin.modern-card class="mt-4">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0" style="color: var(--text-primary); font-weight: 600;">
                            <i class="fas fa-lock me-2" style="color: var(--warning-color);"></i>
                            Ubah Password
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="togglePasswordForm">
                            <i class="fas {{ $showPasswordForm ? 'fa-times' : 'fa-edit' }} me-1"></i>
                            {{ $showPasswordForm ? 'Batal' : 'Ubah' }}
                        </button>
                    </div>

                    @if($showPasswordForm)
                        <form wire:submit="updatePassword">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label">Password Saat Ini <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        id="current_password" wire:model="current_password"
                                        placeholder="Masukkan password saat ini">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password Baru <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" wire:model="password" placeholder="Masukkan password baru">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        wire:model="password_confirmation" placeholder="Konfirmasi password baru">
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-admin.button type="submit" variant="warning" icon="fas fa-key">
                                    <span wire:loading.remove wire:target="updatePassword">Perbarui Password</span>
                                    <span wire:loading wire:target="updatePassword">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Memproses...
                                    </span>
                                </x-admin.button>
                            </div>
                        </form>
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Klik tombol "Ubah" untuk mengubah password Anda.
                        </p>
                    @endif
                </div>
            </x-admin.modern-card>
        </div>
    </div>

<style>
    .photo-upload-container {
        position: relative;
        width: 120px;
        height: 120px;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
    }

    .photo-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 36px;
        height: 36px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        transition: all 0.3s ease;
        border: 3px solid white;
    }

    .photo-upload-btn:hover {
        background: var(--secondary-color);
        transform: scale(1.1);
    }
</style>
</div>