@props(['active' => ''])

<nav class="navbar navbar-expand-lg navbar-dark py-3 glass-nav"
    style="background: linear-gradient(90deg,
                rgba(19, 222, 185, .8) 0%,   /* #13DEB9 80 % */
                rgba(16, 200, 164, .8) 100% /* #10C8A4 80 % */);">
    <style>
        /* GLASS EFFECT */
        .glass-nav {
            backdrop-filter: blur(6px);
            /* blur latar belakang */
            -webkit-backdrop-filter: blur(6px);
            /* utk Safari */
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
        }

        /* brand */
        .navbar-brand span {
            color: #fff;
        }

        /* link dasar */
        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, .15);
            transition: all .25s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            opacity: .9;
            text-decoration: underline;
        }

        /* tombol logout */
        .btn-logout {
            border-color: #fff;
        }

        .btn-logout:hover {
            background: #ffffff22;
        }
    </style>

    <div class="container">
        {{-- Brand --}}
        <a class="navbar-brand fs-4 fw-bold" href="{{ route('home') }}">
            Edu <span>Santri</span>
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Links --}}
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'home' ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
            </ul>

            {{-- Auth --}}
            @auth
                <form method="POST" action="{{ route('logout') }}" class="d-flex">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm btn-logout">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>
