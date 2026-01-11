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
                    <li class="nav-item ms-2">
                        <a href="{{ route('login') }}" class="btn btn-nav">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
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

            @if(!$showResults)
                <!-- Symptom Selection -->
                <div class="diagnosis-card">
                    <div class="card-header-custom">
                        <div class="card-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div>
                            <h3 class="card-title-custom">Pilih Gejala</h3>
                            <p class="card-subtitle">Centang gejala yang terlihat pada ayam</p>
                        </div>
                    </div>

                    <div class="symptoms-grid">
                        @forelse($gejalaList as $gejala)
                            <label class="symptom-item {{ in_array($gejala->id, $selectedGejala) ? 'selected' : '' }}">
                                <input type="checkbox" wire:model.live="selectedGejala" value="{{ $gejala->id }}"
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
                        <div class="selected-count">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ count($selectedGejala) }} gejala dipilih</span>
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
            @else
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
                        <!-- Disease Results -->
                        <div class="disease-results">
                            @foreach($results as $index => $result)
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
                                            {{ $result['matched_count'] }} dari {{ $result['total_gejala'] }} gejala cocok
                                        </span>
                                    </div>

                                    <div class="matched-symptoms">
                                        <strong>Gejala yang cocok:</strong>
                                        <div class="matched-tags">
                                            @foreach($result['matched_gejala'] as $gejala)
                                                <span class="matched-tag">{{ $gejala->kode_gejala }}</span>
                                            @endforeach
                                        </div>
                                    </div>
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
    </style>
</div>
