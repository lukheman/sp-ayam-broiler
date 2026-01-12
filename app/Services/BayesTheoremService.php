<?php

namespace App\Services;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Support\Collection;

class BayesTheoremService
{
    /**
     * Menghitung probabilitas penyakit berdasarkan gejala yang dipilih
     * menggunakan Teorema Bayes.
     *
     * Formula Bayes:
     * P(H|E) = P(E|H) * P(H) / P(E)
     *
     * Dimana:
     * - P(H|E) = Probabilitas hipotesis (penyakit) H terjadi given evidence (gejala) E
     * - P(E|H) = Probabilitas evidence E muncul jika hipotesis H benar (bobot dari basis pengetahuan)
     * - P(H)   = Probabilitas prior hipotesis H (nilai awal penyakit)
     * - P(E)   = Probabilitas evidence E (total probability)
     *
     * @param array $selectedGejalaIds Array ID gejala yang dipilih user
     * @return Collection Hasil diagnosis dengan probabilitas per penyakit
     */
    public function diagnose(array $selectedGejalaIds): Collection
    {
        if (empty($selectedGejalaIds)) {
            return collect([]);
        }

        // Ambil semua penyakit yang memiliki relasi dengan gejala yang dipilih
        $penyakitList = $this->getPenyakitWithSelectedGejala($selectedGejalaIds);

        if ($penyakitList->isEmpty()) {
            return collect([]);
        }

        // Hitung probabilitas prior untuk setiap penyakit
        $totalPenyakit = $penyakitList->count();
        $priorProbability = 1 / $totalPenyakit; // P(H) - asumsi equal prior

        $results = collect();

        foreach ($penyakitList as $penyakit) {
            // Hitung P(E|H) - probabilitas evidence given hypothesis
            $likelihood = $this->calculateLikelihood($penyakit->id, $selectedGejalaIds);

            // Hitung nilai Bayes untuk penyakit ini
            $bayesValue = $this->calculateBayesValue(
                $likelihood,
                $priorProbability,
                $selectedGejalaIds,
                $penyakit->id
            );

            $results->push([
                'penyakit' => $penyakit,
                'likelihood' => $likelihood,
                'prior' => $priorProbability,
                'probability' => $bayesValue,
                'percentage' => round($bayesValue * 100, 2),
                'matched_gejala' => $this->getMatchedGejala($penyakit->id, $selectedGejalaIds),
            ]);
        }

        // Normalisasi hasil agar total = 100%
        $results = $this->normalizeResults($results);

        // Urutkan berdasarkan probabilitas tertinggi
        return $results->sortByDesc('probability')->values();
    }

    /**
     * Ambil penyakit yang memiliki gejala yang dipilih
     */
    protected function getPenyakitWithSelectedGejala(array $gejalaIds): Collection
    {
        return Penyakit::whereHas('gejala', function ($query) use ($gejalaIds) {
            $query->whereIn('gejala.id', $gejalaIds);
        })->with([
                    'gejala' => function ($query) use ($gejalaIds) {
                        $query->whereIn('gejala.id', $gejalaIds);
                    }
                ])->get();
    }

    /**
     * Hitung likelihood P(E|H) - probabilitas evidence given hypothesis
     * Menggunakan bobot dari basis pengetahuan
     */
    protected function calculateLikelihood(int $penyakitId, array $gejalaIds): float
    {
        $basisPengetahuan = BasisPengetahuan::where('id_penyakit', $penyakitId)
            ->whereIn('id_gejala', $gejalaIds)
            ->get();

        if ($basisPengetahuan->isEmpty()) {
            return 0;
        }

        // Hitung rata-rata bobot gejala yang cocok
        $totalBobot = $basisPengetahuan->sum('bobot');
        $countMatched = $basisPengetahuan->count();

        return $countMatched > 0 ? ($totalBobot / $countMatched) : 0;
    }

    /**
     * Hitung nilai Bayes P(H|E)
     */
    protected function calculateBayesValue(
        float $likelihood,
        float $prior,
        array $gejalaIds,
        int $penyakitId
    ): float {
        // P(E|H) * P(H)
        $numerator = $likelihood * $prior;

        // Untuk P(E), kita menggunakan pendekatan simplified
        // dimana P(E) dihitung sebagai sum of all P(E|Hi) * P(Hi)
        // Dalam implementasi ini, kita akan normalisasi di akhir

        return $numerator;
    }

    /**
     * Dapatkan gejala yang cocok antara input user dan basis pengetahuan penyakit
     */
    protected function getMatchedGejala(int $penyakitId, array $gejalaIds): Collection
    {
        return Gejala::whereIn('id', $gejalaIds)
            ->whereHas('basisPengetahuan', function ($query) use ($penyakitId) {
                $query->where('id_penyakit', $penyakitId);
            })
            ->with([
                'basisPengetahuan' => function ($query) use ($penyakitId) {
                    $query->where('id_penyakit', $penyakitId);
                }
            ])
            ->get()
            ->map(function ($gejala) {
                return [
                    'id' => $gejala->id,
                    'kode' => $gejala->kode_gejala,
                    'nama' => $gejala->nama_gejala,
                    'bobot' => $gejala->basisPengetahuan->first()->bobot ?? 0,
                ];
            });
    }

    /**
     * Normalisasi hasil agar total probabilitas = 1 (100%)
     */
    protected function normalizeResults(Collection $results): Collection
    {
        $totalProbability = $results->sum('probability');

        if ($totalProbability == 0) {
            return $results;
        }

        return $results->map(function ($result) use ($totalProbability) {
            $normalizedProbability = $result['probability'] / $totalProbability;
            $result['probability'] = $normalizedProbability;
            $result['percentage'] = round($normalizedProbability * 100, 2);
            return $result;
        });
    }

    /**
     * Mendapatkan semua gejala untuk ditampilkan ke user
     */
    public function getAllGejala(): Collection
    {
        return Gejala::orderBy('kode_gejala', 'asc')->get();
    }

    /**
     * Mendapatkan detail hasil diagnosis dengan penjelasan
     */
    public function getDetailedResult(array $selectedGejalaIds): array
    {
        $results = $this->diagnose($selectedGejalaIds);

        $selectedGejala = Gejala::whereIn('id', $selectedGejalaIds)
            ->orderBy('kode_gejala', 'asc')
            ->get();

        return [
            'selected_gejala' => $selectedGejala,
            'total_gejala_dipilih' => count($selectedGejalaIds),
            'hasil_diagnosis' => $results,
            'penyakit_terdeteksi' => $results->first(),
            'timestamp' => now(),
        ];
    }
}
