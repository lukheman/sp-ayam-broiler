<?php

namespace App\Services;

use App\Models\BasisPengetahuan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class BayesTheoremService
{
    /**
     * Menghitung probabilitas penyakit berdasarkan gejala yang dipilih
     * menggunakan Teorema Bayes dengan 5 tahapan perhitungan.
     *
     * Tahapan:
     * 1. Menjumlahkan nilai probabilitas masing-masing hipotesis: ∑ P(E|H)
     * 2. Menghitung P(H) = P(E|H) / ∑ P(E|H) untuk setiap gejala
     * 3. Menghitung P(H|E sementara) = P(E|H) × P(H) lalu jumlahkan
     * 4. Menghitung P(Hi|E) = (P(H) × P(E|Hi)) / P(E)
     * 5. Menghitung Bayes akhir = P(E|Hi) × P(Hi|E) lalu jumlahkan sebagai persentase
     *
     * @param array $selectedGejalaIds Array ID gejala yang dipilih user
     * @return array Hasil diagnosis dengan probabilitas per penyakit dan detail perhitungan
     */
    public function diagnose(array $selectedGejalaIds): array
    {
        if (empty($selectedGejalaIds)) {
            return [
                'results' => collect([]),
                'calculation_steps' => [],
                'summary' => null,
            ];
        }

        // Ambil semua penyakit yang memiliki relasi dengan gejala yang dipilih
        $penyakitList = $this->getPenyakitWithSelectedGejala($selectedGejalaIds);

        if ($penyakitList->isEmpty()) {
            return [
                'results' => collect([]),
                'calculation_steps' => [],
                'summary' => null,
            ];
        }

        $results = collect();
        $calculationSteps = [];

        foreach ($penyakitList as $penyakit) {
            // Dapatkan detail perhitungan untuk penyakit ini
            $calculation = $this->calculateBayesDetailed($penyakit, $selectedGejalaIds);

            $results->push([
                'penyakit' => $penyakit,
                'bayes_value' => $calculation['total_bayes'],
                'percentage' => $calculation['total_bayes_percentage'],
                'matched_gejala' => $calculation['matched_gejala'],
            ]);

            $calculationSteps[] = $calculation;
        }

        // Urutkan berdasarkan nilai Bayes tertinggi
        $sortedResults = $results->sortByDesc('bayes_value')->values();

        // Sort calculation steps juga
        usort($calculationSteps, function ($a, $b) {
            return $b['total_bayes'] <=> $a['total_bayes'];
        });

        // Generate summary
        $summary = $this->generateCalculationSummary($selectedGejalaIds, $penyakitList);

        return [
            'results' => $sortedResults,
            'calculation_steps' => $calculationSteps,
            'summary' => $summary,
        ];
    }

    /**
     * Hitung Bayes dengan 5 tahapan lengkap untuk satu penyakit
     */
    protected function calculateBayesDetailed(Penyakit $penyakit, array $gejalaIds): array
    {
        // Get matched gejala dengan bobot P(E|H)
        $basisPengetahuan = BasisPengetahuan::where('id_penyakit', $penyakit->id)
            ->whereIn('id_gejala', $gejalaIds)
            ->with('gejala')
            ->get();

        $matchedGejala = $basisPengetahuan->map(function ($bp) {
            return [
                'id' => $bp->gejala->id,
                'kode' => $bp->gejala->kode_gejala,
                'nama' => $bp->gejala->nama_gejala,
                'bobot' => $bp->bobot, // P(E|H)
            ];
        })->toArray();

        // ============================================
        // TAHAP 1: Menjumlahkan nilai probabilitas P(E|H)
        // ∑ P(E|H)
        // ============================================
        $sumPEH = $basisPengetahuan->sum('bobot');

        $step1Details = [];
        $step1Calculation = [];
        foreach ($matchedGejala as $g) {
            $step1Details[] = "{$g['kode']} = {$g['bobot']}";
            $step1Calculation[] = $g['bobot'];
        }

        // ============================================
        // TAHAP 2: Menghitung P(H) untuk setiap gejala
        // P(H) = P(E|H) / ∑ P(E|H)
        // ============================================
        $step2Details = [];
        $gejalaWithPH = [];

        foreach ($matchedGejala as $g) {
            $ph = $sumPEH > 0 ? $g['bobot'] / $sumPEH : 0;
            $gejalaWithPH[] = [
                'kode' => $g['kode'],
                'nama' => $g['nama'],
                'peh' => $g['bobot'],
                'ph' => $ph,
            ];
            $step2Details[] = [
                'gejala' => $g['kode'],
                'calculation' => "P(H|{$g['kode']}) = {$g['bobot']} / " . round($sumPEH, 4),
                'result' => round($ph, 6),
            ];
        }

        // ============================================
        // TAHAP 3: Menghitung P(H|E sementara) = P(E|H) × P(H)
        // Lalu jumlahkan
        // ============================================
        $step3Details = [];
        $sumPHESementara = 0;

        foreach ($gejalaWithPH as $g) {
            $pheSementara = $g['peh'] * $g['ph'];
            $sumPHESementara += $pheSementara;
            $step3Details[] = [
                'gejala' => $g['kode'],
                'calculation' => "P(H|E) = {$g['peh']} × " . round($g['ph'], 6),
                'result' => round($pheSementara, 6),
            ];
        }

        // ============================================
        // TAHAP 4: Menghitung P(Hi|E)
        // P(Hi|E) = (P(H) × P(E|Hi)) / P(E)
        // Dimana P(E) = ∑ P(H|E sementara)
        // ============================================
        $step4Details = [];
        $gejalaWithPHiE = [];
        $pE = $sumPHESementara; // Total probability

        foreach ($gejalaWithPH as $index => $g) {
            $pheSementara = $g['peh'] * $g['ph'];
            $phiE = $pE > 0 ? ($g['ph'] * $g['peh']) / $pE : 0;
            $gejalaWithPHiE[] = [
                'kode' => $g['kode'],
                'peh' => $g['peh'],
                'ph' => $g['ph'],
                'phi_e' => $phiE,
            ];
            $step4Details[] = [
                'gejala' => $g['kode'],
                'calculation' => "P({$penyakit->kode_penyakit}|{$g['kode']}) = (" . round($g['ph'], 6) . " × {$g['peh']}) / " . round($pE, 6),
                'result' => round($phiE, 6),
            ];
        }

        // ============================================
        // TAHAP 5: Menghitung Bayes akhir
        // Bayes = P(E|Hi) × P(Hi|E) untuk setiap gejala
        // Lalu jumlahkan sebagai nilai Bayes total
        // ============================================
        $step5Details = [];
        $totalBayes = 0;

        foreach ($gejalaWithPHiE as $g) {
            $bayesValue = $g['peh'] * $g['phi_e'];
            $totalBayes += $bayesValue;
            $step5Details[] = [
                'gejala' => $g['kode'],
                'calculation' => "Bayes = {$g['peh']} × " . round($g['phi_e'], 6),
                'result' => round($bayesValue, 6),
            ];
        }

        $totalBayesPercentage = round($totalBayes * 100, 2);

        return [
            'penyakit_id' => $penyakit->id,
            'penyakit_kode' => $penyakit->kode_penyakit,
            'penyakit_nama' => $penyakit->nama_penyakit,
            'matched_gejala' => $matchedGejala,
            'matched_count' => count($matchedGejala),
            'steps' => [
                [
                    'step' => 1,
                    'title' => 'Menjumlahkan Nilai Probabilitas P(E|H)',
                    'description' => 'Menjumlahkan nilai probabilitas evidence (gejala) pada hipotesis',
                    'formula' => '∑ P(E|H)',
                    'details' => $step1Details,
                    'calculation' => implode(' + ', $step1Calculation) . ' = ' . round($sumPEH, 4),
                    'result' => round($sumPEH, 4),
                ],
                [
                    'step' => 2,
                    'title' => 'Menghitung P(H) untuk Setiap Gejala',
                    'description' => 'Probabilitas hipotesis tanpa memandang evidence',
                    'formula' => 'P(H) = P(E|H) / ∑ P(E|H)',
                    'details' => $step2Details,
                    'result' => null,
                ],
                [
                    'step' => 3,
                    'title' => 'Menghitung P(H|E) Sementara',
                    'description' => 'Probabilitas hipotesis dengan memandang evidence',
                    'formula' => 'P(H|E sementara) = P(E|H) × P(H)',
                    'details' => $step3Details,
                    'calculation' => '∑ P(H|E) = ' . round($sumPHESementara, 6),
                    'result' => round($sumPHESementara, 6),
                ],
                [
                    'step' => 4,
                    'title' => 'Menghitung Nilai P(Hi|E)',
                    'description' => 'Probabilitas hipotesis akhir',
                    'formula' => 'P(Hi|E) = (P(H) × P(E|Hi)) / P(E)',
                    'details' => $step4Details,
                    'pe_value' => round($pE, 6),
                    'result' => null,
                ],
                [
                    'step' => 5,
                    'title' => 'Menghitung Nilai Bayes Akhir',
                    'description' => 'Nilai Bayes = jumlah dari P(E|Hi) × P(Hi|E)',
                    'formula' => 'Bayes = ∑ [P(E|Hi) × P(Hi|E)]',
                    'details' => $step5Details,
                    'calculation' => '∑ Bayes = ' . round($totalBayes, 6),
                    'result' => round($totalBayes, 6),
                    'percentage' => $totalBayesPercentage,
                ],
            ],
            'sum_peh' => $sumPEH,
            'sum_phe_sementara' => $sumPHESementara,
            'total_bayes' => $totalBayes,
            'total_bayes_percentage' => $totalBayesPercentage,
            'final_percentage' => $totalBayesPercentage,
        ];
    }

    /**
     * Generate summary perhitungan keseluruhan
     */
    protected function generateCalculationSummary(array $gejalaIds, Collection $penyakitList): array
    {
        $selectedGejala = Gejala::whereIn('id', $gejalaIds)->orderBy('kode_gejala')->get();

        return [
            'jumlah_gejala_dipilih' => count($gejalaIds),
            'gejala_dipilih' => $selectedGejala->map(function ($g) {
                return [
                    'kode' => $g->kode_gejala,
                    'nama' => $g->nama_gejala,
                ];
            })->toArray(),
            'jumlah_penyakit_kandidat' => $penyakitList->count(),
            'formula_bayes' => 'Bayes = ∑ [P(E|Hi) × P(Hi|E)]',
            'keterangan' => [
                'P(E|H)' => 'Probabilitas gejala muncul jika penyakit benar (dari bobot basis pengetahuan)',
                'P(H)' => 'Probabilitas hipotesis tanpa memandang evidence = P(E|H) / ∑P(E|H)',
                'P(H|E)' => 'Probabilitas hipotesis dengan memandang evidence = P(E|H) × P(H)',
                'P(Hi|E)' => 'Probabilitas hipotesis akhir = (P(H) × P(E|Hi)) / P(E)',
                'Bayes' => 'Nilai akhir = ∑ [P(E|Hi) × P(Hi|E)] dalam persentase',
            ],
        ];
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
     * Mendapatkan semua gejala untuk ditampilkan ke user
     * Cache selama 60 menit karena data gejala jarang berubah
     */
    public function getAllGejala(): Collection
    {
        return Cache::remember('all_gejala', 3600, function () {
            return Gejala::select('id', 'kode_gejala', 'nama_gejala')
                ->orderBy('kode_gejala', 'asc')
                ->get();
        });
    }
}
