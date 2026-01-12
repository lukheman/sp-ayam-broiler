<div>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-brain"></i>
                    Sistem Pakar Berbasis Web
                </div>
                <h1 class="hero-title">
                    Diagnosa Penyakit<br>
                    <span class="highlight">Ayam Broiler</span>
                </h1>
                <p class="hero-description">
                    Sistem pakar untuk mendiagnosa penyakit pada ayam broiler menggunakan metode Forward Chaining.
                    Dapatkan hasil diagnosa akurat dan rekomendasi penanganan secara instan.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('diagnosa') }}" class="btn-hero-primary">
                        <i class="fas fa-stethoscope"></i>
                        Mulai Diagnosa
                    </a>
                    <!-- <a href="{{ route('login') }}" class="btn-hero-secondary">
                        <i class="fas fa-user-shield"></i>
                        Login Admin
                    </a> -->
                </div>

                <div class="hero-image">
                    <div class="hero-card">
                        <div class="hero-card-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h4 class="text-white mb-2">Diagnosa Cepat & Akurat</h4>
                        <p class="text-white-50 mb-0">
                            Identifikasi penyakit berdasarkan gejala yang terlihat pada ayam broiler Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-section" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <div class="section-badge">Cara Kerja</div>
                <h2 class="section-title">Langkah Mudah Diagnosa</h2>
                <p class="section-description">
                    Proses diagnosa yang sederhana dan cepat dalam 3 langkah mudah
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="step-card">
                        <div class="step-connector d-none d-lg-block"></div>
                        <div class="step-number">1</div>
                        <h3 class="step-title">Pilih Gejala</h3>
                        <p class="step-description">
                            Pilih gejala-gejala yang terlihat pada ayam broiler Anda dari daftar yang tersedia
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="step-card">
                        <div class="step-connector d-none d-lg-block"></div>
                        <div class="step-number">2</div>
                        <h3 class="step-title">Proses Analisis</h3>
                        <p class="step-description">
                            Sistem akan menganalisis gejala menggunakan <i>Teorema Bayes</i> dan <i>Forward Chaining</i>
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h3 class="step-title">Lihat Hasil</h3>
                        <p class="step-description">
                            Dapatkan hasil diagnosa lengkap dengan informasi penyakit 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="about">
        <div class="container">
            <div class="stats-card">
                <div class="row">
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">15+</div>
                            <div class="stat-label">Jenis Penyakit</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label">Gejala Tercatat</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">95%</div>
                            <div class="stat-label">Akurasi Diagnosa</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Tersedia Online</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <h2 class="cta-title">Siap Mendiagnosa?</h2>
                <p class="cta-description">
                    Mulai diagnosa penyakit ayam broiler Anda sekarang dan dapatkan hasil akurat dalam hitungan detik
                </p>
                <a href="{{ route('diagnosa') }}" class="btn-cta">
                    <i class="fas fa-stethoscope"></i>
                    Mulai Diagnosa Sekarang
                </a>
            </div>
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
</div>