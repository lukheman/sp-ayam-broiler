@props([
    'showThemeToggle' => true
])

@php
    $user = Auth::user();
    $userName = $user?->nama ?? 'Guest';
    $userRole = 'Administrator';
    
    // Generate initials from user name
    $initials = '';
    if ($user && $user->nama) {
        $words = explode(' ', $user->nama);
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        $initials = substr($initials, 0, 2);
    } else {
        $initials = 'G';
    }
@endphp

<div class="topbar">
    <div class="d-flex align-items-center">
    </div>
    <div class="d-flex align-items-center gap-3">
        @if($showThemeToggle)
            <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <i id="theme-icon" class="fas fa-moon"></i>
            </button>
        @endif
        <a href="{{ route('admin.profile') }}" class="d-flex align-items-center gap-2 text-decoration-none" title="Profil Pengguna">
            @if($user && $user->photo)
                <img src="{{ Storage::url($user->photo) }}" alt="{{ $userName }}" class="user-avatar-img">
            @else
                <div class="user-avatar">{{ $initials }}</div>
            @endif
            <div class="d-none d-md-block">
                <div class="fw-semibold user-name">{{ $userName }}</div>
                <small class="user-role">{{ $userRole }}</small>
            </div>
        </a>
        @auth
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline">Logout</span>
                </button>
            </form>
        @endauth
        {{ $actions ?? '' }}
    </div>
</div>

<style>
    .user-avatar-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .logout-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: transparent;
        border: 1px solid var(--danger-color);
        border-radius: 8px;
        color: var(--danger-color);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .logout-btn:hover {
        background: var(--danger-color);
        color: white;
    }

    .logout-btn i {
        font-size: 1rem;
    }
</style>
