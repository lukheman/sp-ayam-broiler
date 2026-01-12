<?php

namespace App\Livewire\Admin;

use App\Models\RiwayatDiagnosa;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Riwayat Diagnosis - SP Ayam Broiler')]
class RiwayatDiagnosisManagement extends Component
{
    use WithPagination;

    // Search
    #[Url(as: 'q')]
    public string $search = '';

    // State
    public bool $showDetailModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public ?RiwayatDiagnosa $selectedRiwayat = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openDetailModal(int $id): void
    {
        $this->selectedRiwayat = RiwayatDiagnosa::with(['penyakit', 'gejala'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedRiwayat = null;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            RiwayatDiagnosa::destroy($this->deletingId);
            session()->flash('success', 'Riwayat diagnosis berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function render()
    {
        $riwayatList = RiwayatDiagnosa::query()
            ->with(['penyakit', 'gejala'])
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('penyakit', function ($q) {
                        $q->where('nama_penyakit', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('livewire.admin.riwayat-diagnosis-management', [
            'riwayatList' => $riwayatList,
        ]);
    }
}
