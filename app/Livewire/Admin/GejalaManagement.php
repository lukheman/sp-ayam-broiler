<?php

namespace App\Livewire\Admin;

use App\Models\Gejala;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Gejala - SP Ayam Broiler')]
class GejalaManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $kode_gejala = '';
    public string $nama_gejala = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $rules = [
            'kode_gejala' => ['required', 'string', 'max:10'],
            'nama_gejala' => ['required', 'string', 'max:255'],
        ];

        if ($this->editingId) {
            $rules['kode_gejala'][] = 'unique:gejala,kode_gejala,' . $this->editingId;
        } else {
            $rules['kode_gejala'][] = 'unique:gejala,kode_gejala';
        }

        return $rules;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $gejala = Gejala::findOrFail($id);
        $this->editingId = $id;
        $this->kode_gejala = $gejala->kode_gejala;
        $this->nama_gejala = $gejala->nama_gejala;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $gejala = Gejala::findOrFail($this->editingId);
            $gejala->update($validated);
            session()->flash('success', 'Gejala berhasil diperbarui.');
        } else {
            Gejala::create($validated);
            session()->flash('success', 'Gejala berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Gejala::destroy($this->deletingId);
            session()->flash('success', 'Gejala berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    protected function resetForm(): void
    {
        $this->kode_gejala = '';
        $this->nama_gejala = '';
        $this->editingId = null;
    }

    public function render()
    {
        $gejalaList = Gejala::query()
            ->when($this->search, function ($query) {
                $query->where('kode_gejala', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_gejala', 'like', '%' . $this->search . '%');
            })
            ->orderBy('kode_gejala', 'asc')
            ->paginate(10);

        return view('livewire.admin.gejala-management', [
            'gejalaList' => $gejalaList,
        ]);
    }
}
