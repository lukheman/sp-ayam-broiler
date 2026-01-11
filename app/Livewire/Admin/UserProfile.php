<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.admin.livewire-layout')]
#[Title('Profil Pengguna - SP Ayam Broiler')]
class UserProfile extends Component
{
    use WithFileUploads;

    public string $nama = '';
    public string $email = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public $photo;

    public bool $showPasswordForm = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->nama = $user->nama;
        $this->email = $user->email;
    }

    protected function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ];
    }

    protected function photoRules(): array
    {
        return [
            'photo' => ['required', 'image', 'max:2048'], // max 2MB
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function updateProfile(): void
    {
        $validated = $this->validate();

        $user = Auth::user();
        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->save();

        session()->flash('success', 'Profil berhasil diperbarui.');
    }

    public function updatePhoto(): void
    {
        $this->validate($this->photoRules());

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        // Store new photo
        $path = $this->photo->store('photos', 'public');

        $user->photo = $path;
        $user->save();

        $this->photo = null;

        session()->flash('success', 'Foto profil berhasil diperbarui.');
    }

    public function deletePhoto(): void
    {
        $user = Auth::user();

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->photo = null;
        $user->save();

        session()->flash('success', 'Foto profil berhasil dihapus.');
    }

    public function togglePasswordForm(): void
    {
        $this->showPasswordForm = !$this->showPasswordForm;
        $this->resetPasswordFields();
    }

    public function updatePassword(): void
    {
        $this->validate($this->passwordRules());

        $user = Auth::user();
        $user->password = Hash::make($this->password);
        $user->save();

        $this->resetPasswordFields();
        $this->showPasswordForm = false;

        session()->flash('success', 'Password berhasil diperbarui.');
    }

    protected function resetPasswordFields(): void
    {
        $this->current_password = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function render()
    {
        return view('livewire.admin.user-profile', [
            'user' => Auth::user(),
        ]);
    }
}
