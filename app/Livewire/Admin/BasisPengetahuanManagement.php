<?php

namespace App\Livewire\Admin;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.admin.livewire-layout')]
#[Title('Basis Pengetahuan - SP Ayam Broiler')]
class BasisPengetahuanManagement extends Component
{
    use WithPagination;

    // Search and Filter
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'penyakit')]
    public string $filterPenyakit = '';

    // Form fields
    public string $id_penyakit = '';
    public array $selected_gejala = [];

    // State
    public ?int $editingId = null;
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public bool $showDetailModal = false;
    public ?object $detailPenyakit = null;

    protected function rules(): array
    {
        return [
            'id_penyakit' => ['required', 'exists:penyakit,id'],
            'selected_gejala' => ['required', 'array', 'min:1'],
            'selected_gejala.*' => ['exists:gejala,id'],
        ];
    }

    protected array $messages = [
        'id_penyakit.required' => 'Penyakit harus dipilih.',
        'selected_gejala.required' => 'Minimal pilih satu gejala.',
        'selected_gejala.min' => 'Minimal pilih satu gejala.',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterPenyakit(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $penyakitId): void
    {
        $penyakit = Penyakit::with('gejala')->findOrFail($penyakitId);
        $this->editingId = $penyakitId;
        $this->id_penyakit = (string) $penyakitId;
        $this->selected_gejala = $penyakit->gejala->pluck('id')->toArray();
        $this->showModal = true;
    }

    public function toggleGejala(int $gejalaId): void
    {
        if (in_array($gejalaId, $this->selected_gejala)) {
            $this->selected_gejala = array_values(array_diff($this->selected_gejala, [$gejalaId]));
        } else {
            $this->selected_gejala[] = $gejalaId;
        }
    }

    public function save(): void
    {
        $this->validate();

        $penyakitId = (int) $this->id_penyakit;

        // Delete existing relationships for this penyakit
        BasisPengetahuan::where('id_penyakit', $penyakitId)->delete();

        // Create new relationships
        foreach ($this->selected_gejala as $gejalaId) {
            BasisPengetahuan::create([
                'id_penyakit' => $penyakitId,
                'id_gejala' => (int) $gejalaId,
            ]);
        }

        session()->flash('success', $this->editingId
            ? 'Basis pengetahuan berhasil diperbarui.'
            : 'Basis pengetahuan berhasil ditambahkan.');

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function confirmDelete(int $penyakitId): void
    {
        $this->deletingId = $penyakitId;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            BasisPengetahuan::where('id_penyakit', $this->deletingId)->delete();
            session()->flash('success', 'Basis pengetahuan berhasil dihapus.');
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function showDetail(int $penyakitId): void
    {
        $this->detailPenyakit = Penyakit::with('gejala')->findOrFail($penyakitId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->detailPenyakit = null;
    }

    public function editFromDetail(int $penyakitId): void
    {
        $this->closeDetailModal();
        $this->openEditModal($penyakitId);
    }

    protected function resetForm(): void
    {
        $this->id_penyakit = '';
        $this->selected_gejala = [];
        $this->editingId = null;
    }

    public function render()
    {
        // Get penyakit with their gejala count
        $basisPengetahuan = Penyakit::query()
            ->withCount('gejala')
            ->having('gejala_count', '>', 0)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('kode_penyakit', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_penyakit', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterPenyakit, function ($query) {
                $query->where('id', $this->filterPenyakit);
            })
            ->orderBy('kode_penyakit', 'asc')
            ->paginate(10);

        return view('livewire.admin.basis-pengetahuan-management', [
            'basisPengetahuan' => $basisPengetahuan,
            'penyakitList' => Penyakit::orderBy('kode_penyakit', 'asc')->get(),
            'gejalaList' => Gejala::orderBy('kode_gejala', 'asc')->get(),
        ]);
    }
}
