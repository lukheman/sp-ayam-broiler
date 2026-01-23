<?php

namespace App\Livewire\Admin;

use App\Models\Penyakit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Manajemen Penyakit - SP Ayam Broiler')]
class PenyakitManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // Form fields
    public string $kode_penyakit = '';
    public string $nama_penyakit = '';
    public string $solusi = '';

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        $rules = [
            'kode_penyakit' => ['required', 'string', 'max:10'],
            'nama_penyakit' => ['required', 'string', 'max:255'],
            'solusi' => ['nullable', 'string'],
        ];

        if ($this->editingId) {
            $rules['kode_penyakit'][] = 'unique:penyakit,kode_penyakit,' . $this->editingId;
        } else {
            $rules['kode_penyakit'][] = 'unique:penyakit,kode_penyakit';
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
        $penyakit = Penyakit::findOrFail($id);
        $this->editingId = $id;
        $this->kode_penyakit = $penyakit->kode_penyakit;
        $this->nama_penyakit = $penyakit->nama_penyakit;
        $this->solusi = $penyakit->solusi ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingId) {
            $penyakit = Penyakit::findOrFail($this->editingId);
            $penyakit->update($validated);
            session()->flash('success', 'Penyakit berhasil diperbarui.');
        } else {
            Penyakit::create($validated);
            session()->flash('success', 'Penyakit berhasil ditambahkan.');
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
            Penyakit::destroy($this->deletingId);
            session()->flash('success', 'Penyakit berhasil dihapus.');
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
        $this->kode_penyakit = '';
        $this->nama_penyakit = '';
        $this->solusi = '';
        $this->editingId = null;
    }

    public function render()
    {
        $penyakitList = Penyakit::query()
            ->when($this->search, function ($query) {
                $query->where('kode_penyakit', 'like', '%' . $this->search . '%')
                    ->orWhere('nama_penyakit', 'like', '%' . $this->search . '%');
            })
            ->orderBy('kode_penyakit', 'asc')
            ->paginate(10);

        return view('livewire.admin.penyakit-management', [
            'penyakitList' => $penyakitList,
        ]);
    }
}
