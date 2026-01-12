<div class="diagnosis-container">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-landing">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <div class="brand-icon">
                    <i class="fas fa-feather-alt"></i>
                </div>
                SP Ayam Broiler
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link nav-link-landing" href="{{ route('landing') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-landing" href="#how-it-works">Cara Kerja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-landing" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-landing" href="{{ route('diagnosa') }}">Diagnosa</a>
                    </li>
                    @auth
                        <li class="nav-item ms-2">
                            <a href="{{ route('dashboard') }}" class="btn btn-nav">
                                <i class="fas fa-user-shield me-1"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a href="{{ route('login') }}" class="btn btn-nav">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <section class="diagnosis-section">
        <div class="container">
            <div class="section-header text-center mb-5">
                <div class="section-badge">Sistem Pakar</div>
                <h2 class="section-title">Diagnosa Penyakit Ayam Broiler</h2>
                <p class="section-description">
                    Pilih gejala yang terlihat pada ayam broiler Anda untuk mendapatkan hasil diagnosa
                </p>
            </div>

            @if($currentStep === 1)
                {{-- Step 1: Name Input --}}
                <div class="diagnosis-card">
                    <div class="card-header-custom">
                        <div class="card-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h3 class="card-title-custom">Masukkan Nama Anda</h3>
                            <p class="card-subtitle">Langkah 1 dari 2</p>
                        </div>
                    </div>

                    <div class="name-input-section">
                        <form wire:submit="proceedToSymptoms">
                            <div class="form-group-custom">
                                <label for="nama" class="form-label-custom">
                                    <i class="fas fa-signature me-2"></i>Nama Lengkap
                                </label>
                                <input type="text" id="nama" wire:model="nama"
                                    class="form-input-custom @error('nama') is-invalid @enderror"
                                    placeholder="Masukkan nama Anda..." autofocus>
                                @error('nama')
                                    <div class="error-message">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="card-footer-custom">
                                <div class="step-indicator">
                                    <span class="step active">1</span>
                                    <span class="step-line"></span>
                                    <span class="step">2</span>
                                </div>
                                <button type="submit" class="btn-diagnose">
                                    <span wire:loading.remove wire:target="proceedToSymptoms">
                                        Lanjutkan
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </span>
                                    <span wire:loading wire:target="proceedToSymptoms">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif($currentStep === 2 && !$showResults)
                {{-- Step 2: Symptom Selection --}}
                <div class="diagnosis-card">
                    <div class="card-header-custom">
                        <div class="card-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div>
                            <h3 class="card-title-custom">Pilih Gejala</h3>
                            <p class="card-subtitle">Langkah 2 dari 2 — Halo, <strong>{{ $nama }}</strong></p>
                        </div>
                    </div>

                    <div class="symptoms-grid">
                        @forelse($gejalaList as $gejala)
                            <label class="symptom-item {{ in_array($gejala->id, $selectedGejala) ? 'selected' : '' }}">
                                <input type="checkbox" wire:model="selectedGejala" value="{{ $gejala->id }}"
                                    class="symptom-checkbox">
                                <span class="symptom-code">{{ $gejala->kode_gejala }}</span>
                                <span class="symptom-name">{{ $gejala->nama_gejala }}</span>
                                <span class="symptom-check">
                                    <i class="fas fa-check"></i>
                                </span>
                            </label>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Belum ada data gejala tersedia</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="card-footer-custom">
                        <div class="d-flex align-items-center gap-3">
                            <button wire:click="backToNameInput" class="btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </button>
                            <div class="selected-count">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ count($selectedGejala) }} gejala dipilih</span>
                            </div>
                        </div>
                        <button wire:click="diagnose" class="btn-diagnose" {{ empty($selectedGejala) ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="diagnose">
                                <i class="fas fa-stethoscope me-2"></i>
                                Proses Diagnosa
                            </span>
                            <span wire:loading wire:target="diagnose">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                Menganalisis...
                            </span>
                        </button>
                    </div>
                </div>
            @elseif($showResults)
                <!-- Results Section -->
                <div class="results-container">
                    <div class="results-header">
                        <h3><i class="fas fa-clipboard-check me-2"></i>Hasil Diagnosa</h3>
                        <button wire:click="resetDiagnosis" class="btn-reset">
                            <i class="fas fa-redo-alt me-2"></i>
                            Diagnosa Ulang
                        </button>
                    </div>

                    <!-- Selected Symptoms Summary -->
                    <div class="selected-summary">
                        <h4><i class="fas fa-list-check me-2"></i>Gejala yang Dipilih ({{ count($selectedGejala) }})</h4>
                        <div class="selected-tags">
                            @php
                                $selectedGejalaData = \App\Models\Gejala::whereIn('id', $selectedGejala)->get();
                            @endphp
                            @foreach($selectedGejalaData as $gejala)
                                <span class="symptom-tag">
                                    <strong>{{ $gejala->kode_gejala }}</strong> - {{ $gejala->nama_gejala }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    @if(count($results) > 0)
                        {{-- Summary Calculation Info --}}
                        @if($calculationSummary)
                            <div class="calculation-summary-info mb-4">
                                <div class="summary-header">
                                    <i class="fas fa-calculator me-2"></i>
                                    <span>Metode: <strong>Teorema Bayes</strong></span>
                                    <span class="separator">|</span>
                                    <span>Formula: <code>{{ $calculationSummary['formula_bayes'] }}</code></span>
                                </div>
                                <div class="summary-stats">
                                    <span><i class="fas fa-list-check"></i> {{ $calculationSummary['jumlah_gejala_dipilih'] }}
                                        gejala dipilih</span>
                                    <span><i class="fas fa-virus"></i> {{ $calculationSummary['jumlah_penyakit_kandidat'] }}
                                        kandidat penyakit</span>
                                </div>
                            </div>
                        @endif

                        <!-- Disease Results -->
                        <div class="disease-results">
                            @foreach($results as $index => $result)
                                @php
                                    $calculation = collect($calculationSteps)->firstWhere('penyakit_id', $result['penyakit']->id);
                                @endphp
                                <div class="disease-card {{ $index === 0 ? 'top-result' : '' }}">
                                    @if($index === 0)
                                        <div class="top-badge">
                                            <i class="fas fa-crown"></i> Kemungkinan Tertinggi
                                        </div>
                                    @endif

                                    <div class="disease-header">
                                        <div class="disease-info">
                                            <span class="disease-code">{{ $result['penyakit']->kode_penyakit }}</span>
                                            <h4 class="disease-name">{{ $result['penyakit']->nama_penyakit }}</h4>
                                        </div>
                                        <div
                                            class="disease-percentage {{ $result['percentage'] >= 70 ? 'high' : ($result['percentage'] >= 40 ? 'medium' : 'low') }}">
                                            {{ $result['percentage'] }}%
                                        </div>
                                    </div>

                                    <div class="progress-bar-custom">
                                        <div class="progress-fill {{ $result['percentage'] >= 70 ? 'high' : ($result['percentage'] >= 40 ? 'medium' : 'low') }}"
                                            style="width: {{ $result['percentage'] }}%">
                                        </div>
                                    </div>

                                    <div class="disease-details">
                                        <span class="match-count">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $result['matched_count'] }} gejala cocok
                                        </span>
                                    </div>

                                    <div class="matched-symptoms">
                                        <strong>Gejala yang cocok:</strong>
                                        <div class="matched-tags">
                                            @foreach($result['matched_gejala'] as $gejala)
                                                <span class="matched-tag" title="Bobot: {{ $gejala['bobot'] }}">
                                                    {{ $gejala['kode'] }} <small>({{ $gejala['bobot'] }})</small>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Calculation Details Toggle --}}
                                    @if($calculation)
                                        <div class="calc-toggle-section">
                                            <button class="calc-toggle-btn"
                                                onclick="this.parentElement.querySelector('.calc-details').classList.toggle('show'); this.classList.toggle('active');">
                                                <i class="fas fa-calculator me-1"></i>
                                                <span>Lihat Detail Proses Perhitungan</span>
                                                <i class="fas fa-chevron-down toggle-arrow"></i>
                                            </button>

                                            <div class="calc-details">
                                                <div class="calc-steps-wrapper">
                                                    @foreach($calculation['steps'] as $step)
                                                        <div class="calc-step-box">
                                                            <div class="calc-step-header">
                                                                <span class="calc-step-badge">Tahap {{ $step['step'] }}</span>
                                                                <span class="calc-step-title-main">{{ $step['title'] }}</span>
                                                            </div>
                                                            <div class="calc-step-body">
                                                                <div class="calc-step-desc">{{ $step['description'] }}</div>
                                                                <div class="calc-step-formula">
                                                                    <strong>Formula:</strong> <code>{{ $step['formula'] }}</code>
                                                                </div>

                                                                {{-- Detail per gejala --}}
                                                                @if(isset($step['details']) && is_array($step['details']) && count($step['details']) > 0)
                                                                    <div class="calc-details-table">
                                                                        @if($step['step'] == 1)
                                                                            {{-- Tahap 1: Daftar P(E|H) --}}
                                                                            <div class="detail-list">
                                                                                @foreach($step['details'] as $detail)
                                                                                    <span class="detail-item">{{ $detail }}</span>
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            {{-- Tahap 2-5: Tabel perhitungan per gejala --}}
                                                                            <table class="calc-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Gejala</th>
                                                                                        <th>Perhitungan</th>
                                                                                        <th>Hasil</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($step['details'] as $detail)
                                                                                        <tr>
                                                                                            <td><code>{{ $detail['gejala'] }}</code></td>
                                                                                            <td>{{ $detail['calculation'] }}</td>
                                                                                            <td><strong>{{ $detail['result'] }}</strong></td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        @endif
                                                                    </div>
                                                                @endif

                                                                {{-- Hasil perhitungan --}}
                                                                @if(isset($step['calculation']))
                                                                    <div class="calc-step-calculation">
                                                                        <strong>Perhitungan:</strong> {{ $step['calculation'] }}
                                                                    </div>
                                                                @endif

                                                                @if(isset($step['result']) && $step['result'] !== null)
                                                                    <div class="calc-step-result-box">
                                                                        <strong>Hasil:</strong>
                                                                        <span class="result-value">{{ $step['result'] }}</span>
                                                                        @if(isset($step['percentage']))
                                                                            <span class="calc-percentage-badge">{{ $step['percentage'] }}%</span>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    {{-- Kesimpulan --}}
                                                    <div class="calc-conclusion">
                                                        <div class="conclusion-header">
                                                            <i class="fas fa-flag-checkered me-2"></i>
                                                            Kesimpulan untuk {{ $calculation['penyakit_kode'] }}
                                                        </div>
                                                        <div class="conclusion-body">
                                                            <div class="conclusion-value">
                                                                <span class="label">Nilai Bayes:</span>
                                                                <span class="value">{{ round($calculation['total_bayes'], 6) }}</span>
                                                            </div>
                                                            <div class="conclusion-percentage">
                                                                <span class="label">Persentase:</span>
                                                                <span
                                                                    class="percentage-value {{ $calculation['total_bayes_percentage'] >= 70 ? 'high' : ($calculation['total_bayes_percentage'] >= 40 ? 'medium' : 'low') }}">
                                                                    {{ $calculation['total_bayes_percentage'] }}%
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-results">
                            <div class="no-results-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h4>Tidak Ditemukan Kecocokan</h4>
                            <p>Gejala yang dipilih tidak cocok dengan penyakit yang ada dalam database</p>
                            <button wire:click="resetDiagnosis" class="btn-retry">
                                <i class="fas fa-redo-alt me-2"></i>
                                Coba Lagi
                            </button>
                        </div>
                    @endif

                    <div class="disclaimer mt-4">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>
                            <strong>Perhatian:</strong> Hasil diagnosa ini bersifat prediksi berdasarkan gejala yang
                            dipilih.
                            Untuk penanganan yang tepat, konsultasikan dengan dokter hewan atau ahli peternakan.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="brand-icon" style="width: 35px; height: 35px; font-size: 1rem;">
                        <i class="fas fa-feather-alt"></i>
                    </div>
                    SP Ayam Broiler
                </div>
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} SP Ayam Broiler. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <style>
        .diagnosis-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .diagnosis-section {
            flex: 1;
            padding: 8rem 0 4rem;
            position: relative;
            z-index: 1;
        }

        .diagnosis-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .card-title-custom {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .card-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
            margin: 0;
        }

        .symptoms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
            padding: 2rem;
            max-height: 500px;
            overflow-y: auto;
        }

        .symptom-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .symptom-item:hover {
            border-color: #6366f1;
            background: #f0f1ff;
        }

        .symptom-item.selected {
            border-color: #6366f1;
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        }

        .symptom-checkbox {
            display: none;
        }

        .symptom-code {
            background: #6366f1;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .symptom-name {
            flex: 1;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .symptom-check {
            width: 24px;
            height: 24px;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: transparent;
        }

        .symptom-item.selected .symptom-check {
            background: #6366f1;
            border-color: #6366f1;
            color: white;
        }

        .card-footer-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .selected-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6366f1;
            font-weight: 600;
        }

        .btn-diagnose {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .btn-diagnose:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.5);
        }

        .btn-diagnose:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Name Input Section Styles */
        .name-input-section {
            padding: 2rem;
        }

        .form-group-custom {
            margin-bottom: 1.5rem;
        }

        .form-label-custom {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .form-input-custom {
            width: 100%;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            transition: all 0.3s ease;
            color: #1e293b;
        }

        .form-input-custom:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-input-custom.is-invalid {
            border-color: #ef4444;
        }

        .form-input-custom::placeholder {
            color: #94a3b8;
        }

        .error-message {
            margin-top: 0.5rem;
            color: #ef4444;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .step-indicator .step {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .step-indicator .step.active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
        }

        .step-indicator .step-line {
            width: 30px;
            height: 3px;
            background: #e2e8f0;
            border-radius: 2px;
        }

        .btn-back {
            background: transparent;
            border: 2px solid #e2e8f0;
            color: #64748b;
            padding: 0.75rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            border-color: #6366f1;
            color: #6366f1;
            background: #f0f1ff;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Results Styles */
        .results-container {
            animation: fadeInUp 0.5s ease-out;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .results-header h3 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .btn-reset {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: white;
        }

        .selected-summary {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .selected-summary h4 {
            color: white;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 1rem 0;
        }

        .selected-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .symptom-tag {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .disease-results {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .disease-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            transition: all 0.3s ease;
        }

        .disease-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .disease-card.top-result {
            border: 2px solid #f59e0b;
            box-shadow: 0 15px 40px rgba(245, 158, 11, 0.2);
        }

        .top-badge {
            position: absolute;
            top: -12px;
            left: 1.5rem;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .disease-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            margin-top: 0.5rem;
        }

        .disease-code {
            background: #6366f1;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .disease-name {
            color: #1e293b;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .disease-percentage {
            font-size: 2rem;
            font-weight: 800;
            padding: 0.5rem 1rem;
            border-radius: 12px;
        }

        .disease-percentage.high {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

        .disease-percentage.medium {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }

        .disease-percentage.low {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .progress-bar-custom {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-out;
        }

        .progress-fill.high {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .progress-fill.medium {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .progress-fill.low {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        .disease-details {
            margin-bottom: 1rem;
        }

        .match-count {
            color: #64748b;
            font-size: 0.9rem;
        }

        .match-count i {
            color: #10b981;
            margin-right: 0.25rem;
        }

        .matched-symptoms {
            font-size: 0.9rem;
            color: #64748b;
        }

        .matched-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .matched-tag {
            background: #eef2ff;
            color: #6366f1;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .no-results {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .no-results-icon {
            width: 80px;
            height: 80px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: #94a3b8;
        }

        .no-results h4 {
            color: #1e293b;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .no-results p {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .btn-retry {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-retry:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
        }

        .disclaimer {
            display: flex;
            gap: 1rem;
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            color: white;
        }

        .disclaimer i {
            color: #f59e0b;
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.25rem;
        }

        .disclaimer p {
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .symptoms-grid {
                grid-template-columns: 1fr;
                padding: 1rem;
            }

            .card-footer-custom {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-diagnose {
                width: 100%;
            }

            .results-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .disease-header {
                flex-direction: column;
                gap: 1rem;
            }

            .disease-percentage {
                font-size: 1.5rem;
            }
        }

        /* Calculation Section Styles */
        .calculation-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .calculation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .calculation-header:hover {
            background: linear-gradient(135deg, #2d4a6f 0%, #1f273a 100%);
        }

        .calculation-header h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .calculation-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }

        .calculation-content.show {
            max-height: 5000px;
            padding: 1.5rem;
        }

        .summary-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #bae6fd;
        }

        .summary-box h5 {
            color: #0369a1;
            margin: 0 0 1rem 0;
            font-size: 1rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .summary-item {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .summary-item .label {
            display: block;
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .summary-item .value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0369a1;
        }

        .formula-box {
            background: white;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }

        .formula-box code {
            font-size: 1.1rem;
            color: #6366f1;
            font-weight: 600;
        }

        .keterangan {
            font-size: 0.85rem;
            color: #475569;
        }

        .keterangan ul {
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }

        .keterangan li {
            margin-bottom: 0.25rem;
        }

        .keterangan code {
            background: #e0e7ff;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            color: #4338ca;
            font-weight: 600;
        }

        .penyakit-calculation {
            background: #f8fafc;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .penyakit-calculation:hover {
            border-color: #6366f1;
        }

        .penyakit-calculation.top-ranked {
            border-color: #f59e0b;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        .penyakit-calc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .penyakit-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .rank-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .rank-badge-normal {
            background: #e2e8f0;
            color: #475569;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .penyakit-code {
            background: #6366f1;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .penyakit-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
        }

        .penyakit-result .percentage {
            font-size: 1.5rem;
            font-weight: 800;
            padding: 0.5rem 1rem;
            border-radius: 10px;
        }

        .penyakit-result .percentage.high {
            color: #10b981;
            background: rgba(16, 185, 129, 0.1);
        }

        .penyakit-result .percentage.medium {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }

        .penyakit-result .percentage.low {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .matched-gejala-section {
            margin-bottom: 1rem;
        }

        .gejala-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .gejala-tag {
            background: #e0e7ff;
            color: #4338ca;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .gejala-tag small {
            opacity: 0.7;
        }

        .steps-container {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }

        .step-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .step-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .step-item:first-child {
            padding-top: 0;
        }

        .step-number {
            width: 32px;
            height: 32px;
            min-width: 32px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .step-formula,
        .step-calc,
        .step-result {
            font-size: 0.9rem;
            color: #475569;
            margin-bottom: 0.25rem;
        }

        .step-formula code {
            background: #f1f5f9;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            color: #6366f1;
        }

        .step-result {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px dashed #e2e8f0;
        }

        .result-value {
            font-weight: 700;
            color: #10b981;
            font-size: 1rem;
        }

        .result-percentage {
            background: #10b981;
            color: white;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .step-detail {
            margin-top: 0.5rem;
            padding: 0.5rem;
            background: #f8fafc;
            border-radius: 6px;
        }

        /* Integrated Calculation Styles */
        .calculation-summary-info {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            border: 2px solid #c7d2fe;
        }

        .summary-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #1e293b;
            font-size: 0.95rem;
            flex-wrap: wrap;
        }

        .summary-header i {
            color: #6366f1;
        }

        .summary-header .separator {
            opacity: 0.4;
            color: #6366f1;
        }

        .summary-header code {
            background: #6366f1;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
            color: white;
        }

        .summary-stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.75rem;
            flex-wrap: wrap;
        }

        .summary-stats span {
            color: #475569;
            font-size: 0.85rem;
        }

        .summary-stats i {
            margin-right: 0.35rem;
            color: #6366f1;
        }

        .calc-toggle-section {
            margin-top: 1rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 1rem;
        }

        .calc-toggle-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%);
            border: 1px solid #c7d2fe;
            color: #4f46e5;
            padding: 0.6rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
        }

        .calc-toggle-btn:hover {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        }

        .calc-toggle-btn.active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border-color: #4f46e5;
        }

        .calc-toggle-btn .toggle-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .calc-toggle-btn.active .toggle-arrow {
            transform: rotate(180deg);
        }

        .calc-details {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease-in-out;
        }

        .calc-details.show {
            max-height: none;
            overflow: visible;
            padding-top: 1rem;
        }

        .calc-steps-wrapper {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }

        .calc-step {
            display: flex;
            gap: 0.75rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .calc-step:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .calc-step:first-child {
            padding-top: 0;
        }

        .calc-step-num {
            width: 28px;
            height: 28px;
            min-width: 28px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .calc-step-content {
            flex: 1;
            font-size: 0.85rem;
        }

        .calc-step-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.35rem;
        }

        .calc-step-formula {
            margin-bottom: 0.5rem;
        }

        .calc-step-formula code {
            background: #e0e7ff;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            color: #4338ca;
            font-size: 0.85rem;
        }

        .calc-step-calc {
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .calc-step-result {
            color: #1e293b;
            margin-top: 0.25rem;
        }

        .calc-step-result strong {
            color: #10b981;
            font-size: 1rem;
        }

        .calc-percentage {
            background: #10b981;
            color: white;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 0.35rem;
        }

        .calc-step-detail {
            margin-top: 0.35rem;
            padding: 0.35rem 0.5rem;
            background: #f1f5f9;
            border-radius: 6px;
            color: #64748b;
        }

        .matched-tag small {
            opacity: 0.7;
            font-size: 0.75rem;
        }

        /* New 5-Step Calculation Styles */
        .calc-step-box {
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .calc-step-box:last-of-type {
            margin-bottom: 0;
        }

        .calc-step-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .calc-step-badge {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .calc-step-title-main {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }

        .calc-step-body {
            padding: 1rem;
        }

        .calc-step-desc {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }

        .calc-details-table {
            margin: 0.75rem 0;
        }

        .detail-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .detail-item {
            background: #e0e7ff;
            color: #4338ca;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .calc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .calc-table th,
        .calc-table td {
            padding: 0.5rem 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .calc-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            font-size: 0.8rem;
        }

        .calc-table td {
            color: #1e293b;
        }

        .calc-table td code {
            background: #e0e7ff;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            color: #4338ca;
            font-weight: 600;
        }

        .calc-table tbody tr:last-child td {
            border-bottom: none;
        }

        .calc-table tbody tr:hover {
            background: #f8fafc;
        }

        .calc-step-calculation {
            margin-top: 0.75rem;
            padding: 0.5rem 0.75rem;
            background: #f8fafc;
            border-radius: 6px;
            font-size: 0.85rem;
            color: #475569;
        }

        .calc-step-result-box {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-radius: 8px;
            border: 1px solid #a7f3d0;
        }

        .calc-step-result-box .result-value {
            font-weight: 700;
            color: #059669;
            font-size: 1.1rem;
        }

        .calc-percentage-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-left: 0.5rem;
        }

        .calc-conclusion {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 12px;
            margin-top: 1.5rem;
            border: 2px solid #fbbf24;
            overflow: hidden;
        }

        .conclusion-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .conclusion-body {
            padding: 1rem;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .conclusion-value,
        .conclusion-percentage {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .conclusion-value .label,
        .conclusion-percentage .label {
            font-size: 0.8rem;
            color: #92400e;
            font-weight: 500;
        }

        .conclusion-value .value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #78350f;
        }

        .percentage-value {
            font-size: 1.5rem;
            font-weight: 800;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
        }

        .percentage-value.high {
            color: white;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .percentage-value.medium {
            color: white;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .percentage-value.low {
            color: white;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        @media (max-width: 768px) {
            .calc-step-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .calc-table {
                font-size: 0.75rem;
            }

            .calc-table th,
            .calc-table td {
                padding: 0.35rem 0.5rem;
            }

            .conclusion-body {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</div>