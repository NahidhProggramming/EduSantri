@props(['active' => ''])

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #13DEB9;">
    <style>
        .navbar-nav .nav-link {
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link:hover {
            opacity: 0.9;
            text-decoration: underline;
        }
    </style>
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Edu <span>Santri</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'home' ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'profile' ? 'active' : '' }}" href="#">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'contact' ? 'active' : '' }}" href="#">Kontak</a>
                </li>
            </ul>
            @auth
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light ms-2">
                                <i class="bi bi-box-arrow-right"></i> Logout
                        </form>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>
