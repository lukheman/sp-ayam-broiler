<?php

namespace App\Livewire;

use App\Models\Gejala;
use App\Models\Penyakit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.landing.layout')]
#[Title('Diagnosa Penyakit - SP Ayam Broiler')]
class Diagnosis extends Component
{
    public array $selectedGejala = [];
    public array $results = [];
    public bool $showResults = false;

    /**
     * Perform diagnosis using Forward Chaining
     */
    public function diagnose(): void
    {
        if (empty($this->selectedGejala)) {
            return;
        }

        $results = [];

        // Get all diseases with their symptoms
        $penyakitList = Penyakit::with('gejala')->get();

        foreach ($penyakitList as $penyakit) {
            $penyakitGejalaIds = $penyakit->gejala->pluck('id')->toArray();

            if (empty($penyakitGejalaIds)) {
                continue;
            }

            // Find matching symptoms
            $matchedGejalaIds = array_intersect($this->selectedGejala, $penyakitGejalaIds);
            $matchedCount = count($matchedGejalaIds);

            if ($matchedCount > 0) {
                // Calculate match percentage
                $percentage = ($matchedCount / count($penyakitGejalaIds)) * 100;

                // Get matched symptom names
                $matchedGejala = Gejala::whereIn('id', $matchedGejalaIds)->get();

                $results[] = [
                    'penyakit' => $penyakit,
                    'percentage' => round($percentage, 2),
                    'matched_count' => $matchedCount,
                    'total_gejala' => count($penyakitGejalaIds),
                    'matched_gejala' => $matchedGejala,
                ];
            }
        }

        // Sort by percentage descending
        usort($results, fn($a, $b) => $b['percentage'] <=> $a['percentage']);

        $this->results = $results;
        $this->showResults = true;
    }

    /**
     * Reset diagnosis to start over
     */
    public function resetDiagnosis(): void
    {
        $this->selectedGejala = [];
        $this->results = [];
        $this->showResults = false;
    }

    public function render()
    {
        $gejalaList = Gejala::orderBy('kode_gejala', 'asc')->get();

        return view('livewire.diagnosis', [
            'gejalaList' => $gejalaList,
        ]);
    }
}
