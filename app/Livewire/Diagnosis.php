<?php

namespace App\Livewire;

use App\Models\Gejala;
use App\Models\RiwayatDiagnosa;
use App\Models\RiwayatDiagnosaGejala;
use App\Services\BayesTheoremService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.landing.layout')]
#[Title('Diagnosa Penyakit - SP Ayam Broiler')]
class Diagnosis extends Component
{
    // User input
    public string $nama = '';
    public int $currentStep = 1; // 1 = nama input, 2 = gejala selection, 3 = results

    public array $selectedGejala = [];
    public array $results = [];
    public bool $showResults = false;
    public ?array $topResult = null;
    public array $calculationSteps = [];
    public ?array $calculationSummary = null;

    protected BayesTheoremService $bayesService;

    public function boot(BayesTheoremService $bayesService): void
    {
        $this->bayesService = $bayesService;
    }

    /**
     * Proceed to symptom selection step after entering name
     */
    public function proceedToSymptoms(): void
    {
        $this->validate([
            'nama' => ['required', 'string', 'min:2', 'max:100'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama minimal 2 karakter.',
            'nama.max' => 'Nama maksimal 100 karakter.',
        ]);

        $this->currentStep = 2;
    }

    /**
     * Go back to name input step
     */
    public function backToNameInput(): void
    {
        $this->currentStep = 1;
    }

    /**
     * Perform diagnosis using Bayes Theorem
     */
    public function diagnose(): void
    {
        if (empty($this->selectedGejala)) {
            return;
        }

        // Convert string IDs to integers
        $gejalaIds = array_map('intval', $this->selectedGejala);

        // Get diagnosis results using Bayes Theorem
        $diagnosisData = $this->bayesService->diagnose($gejalaIds);
        $diagnosisResults = $diagnosisData['results'];

        if ($diagnosisResults->isEmpty()) {
            $this->results = [];
            $this->topResult = null;
            $this->calculationSteps = [];
            $this->calculationSummary = null;
            $this->showResults = true;
            return;
        }

        // Convert to array for Livewire
        $this->results = $diagnosisResults->map(function ($result) {
            $matchedGejala = $result['matched_gejala'];
            // Ensure it's an array
            if ($matchedGejala instanceof \Illuminate\Support\Collection) {
                $matchedGejala = $matchedGejala->toArray();
            }
            return [
                'penyakit' => $result['penyakit'],
                'percentage' => $result['percentage'],
                'bayes_value' => $result['bayes_value'],
                'matched_gejala' => $matchedGejala,
                'matched_count' => is_countable($matchedGejala) ? count($matchedGejala) : 0,
            ];
        })->toArray();

        // Store calculation details
        $this->calculationSteps = $diagnosisData['calculation_steps'];
        $this->calculationSummary = $diagnosisData['summary'];

        $this->topResult = $this->results[0] ?? null;
        $this->showResults = true;

        // Save diagnosis history if user is authenticated
        $this->saveDiagnosisHistory($gejalaIds);
    }

    /**
     * Save diagnosis history to database
     */
    protected function saveDiagnosisHistory(array $gejalaIds): void
    {
        if (!$this->topResult) {
            return;
        }

        // Use the name provided by user
        $namaUser = $this->nama ?: (Auth::check() ? Auth::user()->nama : 'Tamu');

        // Create riwayat diagnosa record
        $riwayat = RiwayatDiagnosa::create([
            'tanggal' => now()->toDateString(),
            'nama' => $namaUser,
            'id_penyakit' => $this->topResult['penyakit']->id,
        ]);

        // Save selected gejala to riwayat
        foreach ($gejalaIds as $gejalaId) {
            RiwayatDiagnosaGejala::create([
                'id_riwayat_diagnosa' => $riwayat->id,
                'id_gejala' => $gejalaId,
            ]);
        }
    }

    /**
     * Toggle gejala selection
     */
    public function toggleGejala(int $gejalaId): void
    {
        if (in_array($gejalaId, $this->selectedGejala)) {
            $this->selectedGejala = array_values(array_diff($this->selectedGejala, [$gejalaId]));
        } else {
            $this->selectedGejala[] = $gejalaId;
        }
    }

    /**
     * Reset diagnosis to start over
     */
    public function resetDiagnosis(): void
    {
        $this->selectedGejala = [];
        $this->results = [];
        $this->topResult = null;
        $this->calculationSteps = [];
        $this->calculationSummary = null;
        $this->showResults = false;
        $this->currentStep = 2; // Go back to symptom selection, keep the name
    }

    /**
     * Start over completely (including name)
     */
    public function startOver(): void
    {
        $this->nama = '';
        $this->selectedGejala = [];
        $this->results = [];
        $this->topResult = null;
        $this->calculationSteps = [];
        $this->calculationSummary = null;
        $this->showResults = false;
        $this->currentStep = 1;
    }

    public function render()
    {
        $gejalaList = Gejala::orderBy('kode_gejala', 'asc')->get();

        return view('livewire.diagnosis', [
            'gejalaList' => $gejalaList,
        ]);
    }
}
