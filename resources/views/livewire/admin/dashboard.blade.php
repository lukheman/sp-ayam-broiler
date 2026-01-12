<div>
    {{-- Page Header --}}
    <x-admin.page-header title="Dashboard" subtitle="Selamat datang di Sistem Pakar Diagnosa Penyakit Ayam Broiler">
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--primary-color);">
                <div class="stat-icon"
                    style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(99, 102, 241, 0.05));">
                    <i class="fas fa-virus" style="color: var(--primary-color);"></i>
                </div>
                <div class="stat-value">{{ $penyakitCount }}</div>
                <div class="stat-label">Total Penyakit</div>
                <a href="{{ route('admin.penyakit') }}" class="stat-link">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--success-color);">
                <div class="stat-icon"
                    style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.05));">
                    <i class="fas fa-notes-medical" style="color: var(--success-color);"></i>
                </div>
                <div class="stat-value">{{ $gejalaCount }}</div>
                <div class="stat-label">Total Gejala</div>
                <a href="{{ route('admin.gejala') }}" class="stat-link">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="--accent-color: var(--warning-color);">
                <div class="stat-icon"
                    style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.05));">
                    <i class="fas fa-brain" style="color: var(--warning-color);"></i>
                </div>
                <div class="stat-value">{{ $basisPengetahuanCount }}</div>
                <div class="stat-label">Basis Pengetahuan</div>
                <a href="{{ route('admin.basis-pengetahuan') }}" class="stat-link">
                    Lihat semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>


<style>
    .stat-card {
        padding: 1.5rem;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .stat-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--primary-color);
        text-decoration: none;
        transition: all 0.2s;
    }

    .stat-link:hover {
        gap: 0.75rem;
        color: var(--primary-dark);
    }

    .quick-link-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .quick-link-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .quick-link-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .quick-link-content h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    .quick-link-content p {
        margin: 0.25rem 0 0;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
</style>
</div>