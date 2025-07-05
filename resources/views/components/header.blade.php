<!--  Header Start -->
<style>
    @media (max-width: 768px) {
        .header-user-info {
            font-size: 0.75rem;
        }

        .header-user-role {
            padding: 0.1rem 0.3rem;
            font-size: 0.65rem;
        }
    }
</style>
<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="d-flex align-items-center gap-1">
                            <img src="{{ asset('images/profile/user-1.jpg') }}" alt="User" width="32"
                                height="32" class="rounded-circle border border-white">
                            <div class="d-flex flex-column header-user-info" style="line-height: 1.1">
                                @auth
                                    <span class="fw-bold small header-user-name"
                                        style="margin-bottom: 1px">{{ Auth::user()->name ?? 'Guest' }}</span>
                                    <span class="badge bg-success text-white small header-user-role"
                                        style="padding: 0.15rem 0.4rem; margin-top: 1px">
                                        @if (Auth::user()->roles->isNotEmpty())
                                            {{ ucfirst(str_replace('_', ' ', Auth::user()->getRoleNames()->first())) }}
                                        @else
                                            User
                                        @endif
                                    </span>
                                @else
                                    <span class="fw-bold small header-user-name" style="margin-bottom: 1px">Guest</span>
                                    <span class="badge bg-secondary text-white small header-user-role"
                                        style="padding: 0.15rem 0.4rem; margin-top: 1px">
                                        Guest
                                    </span>
                                @endauth
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up mt-2" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">My Profile</p>
                            </a>
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-mail fs-6"></i>
                                <p class="mb-0 fs-3">My Account</p>
                            </a>
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-list-check fs-6"></i>
                                <p class="mb-0 fs-3">My Task</p>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary mx-3 mt-2 d-block w-100">
                                    <i class="ti ti-logout fs-6 me-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--  Header End -->
