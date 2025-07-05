<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/" class="text-nowrap logo-img">
                <img src="{{ asset('images/logos/logo2.png') }}" width="200" alt="" />
            </a>

            @guest
                <div class="ms-auto">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
                </div>
            @endguest
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        {{-- <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/" aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Menu</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/santri" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Data Santri</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/nilai" aria-expanded="false">
                        <span>
                            <i class="ti ti-book"></i>
                        </span>
                        <span class="hide-menu">Nilai Akademik</span>
                    </a>
                </li>

                <!-- Dashboard -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/dashboard" aria-expanded="false">
                        <span>
                            <i class="ti ti-home"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/santri" aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Data Santri</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/orang-tua" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-heart"></i>
                        </span>
                        <span class="hide-menu">Data Orang Tua</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/kelas" aria-expanded="false">
                        <span>
                            <i class="ti ti-building-arch"></i>
                        </span>
                        <span class="hide-menu">Data Kelas & Sekolah</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/asrama" aria-expanded="false">
                        <span>
                            <i class="ti ti-bed"></i>
                        </span>
                        <span class="hide-menu">Data Asrama</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/pelanggaran/poin" aria-expanded="false">
                        <span>
                            <i class="ti ti-flag"></i>
                        </span>
                        <span class="hide-menu">Poin Pelanggaran</span>
                    </a>
                </li>

                <!-- Akademik -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/akademik/input-nilai" aria-expanded="false">
                        <span>
                            <i class="ti ti-pencil"></i>
                        </span>
                        <span class="hide-menu">Input Nilai Santri</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/akademik/data-nilai" aria-expanded="false">
                        <span>
                            <i class="ti ti-list-details"></i>
                        </span>
                        <span class="hide-menu">Data Nilai</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/akademik/kenaikan-kelas" aria-expanded="false">
                        <span>
                            <i class="ti ti-arrow-up"></i>
                        </span>
                        <span class="hide-menu">Kenaikan Kelas</span>
                    </a>
                </li>

                <!-- Pelanggaran -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/pelanggaran/catat" aria-expanded="false">
                        <span>
                            <i class="ti ti-alert-circle"></i>
                        </span>
                        <span class="hide-menu">Catat Pelanggaran</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/pelanggaran/rekap" aria-expanded="false">
                        <span>
                            <i class="ti ti-file-info"></i>
                        </span>
                        <span class="hide-menu">Rekap Pelanggaran</span>
                    </a>
                </li>

                <!-- Statistik -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/statistik/akademik" aria-expanded="false">
                        <span>
                            <i class="ti ti-chart-bar"></i>
                        </span>
                        <span class="hide-menu">Statistik Akademik</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="/statistik/pelanggaran" aria-expanded="false">
                        <span>
                            <i class="ti ti-chart-pie"></i>
                        </span>
                        <span class="hide-menu">Statistik Pelanggaran</span>
                    </a>
                </li>

                <!-- Pengaturan Notifikasi -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/pengaturan/notifikasi" aria-expanded="false">
                        <span>
                            <i class="ti ti-bell"></i>
                        </span>
                        <span class="hide-menu">Pengaturan Notifikasi</span>
                    </a>
                </li>

                <!-- Pengguna -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/pengguna" aria-expanded="false">
                        <span>
                            <i class="ti ti-user-cog"></i>
                        </span>
                        <span class="hide-menu">Pengguna & Akses</span>
                    </a>
                </li>

            </ul>
        </nav> --}}

        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <!-- Menu Umum -->
                {{-- <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="#" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li> --}}
                @auth
                    @if (auth()->user()->hasRole('admin'))
                        <!-- Menu Admin -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Admin Menu</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-adjustments-horizontal me-2"></i>
                                    <span class="hide-menu">Konfigurasi</span>
                                    <i
                                        class="dropdown-icon ti {{ request()->is('akademik*') ? 'ti-chevron-down' : 'ti-chevron-right' }} me-2"></i>
                                </span>
                            </a>
                            <ul class="collapse first-level ms-4"
                                style="{{ request()->is('akademik*') ? 'display: block;' : 'display: none;' }}">
                                <li class="sidebar-item {{ request()->is('akademik*') ? 'active bg-light' : '' }}">
                                    <a href="/akademik" class="sidebar-link d-flex align-items-center">
                                        <span>
                                            <i class="ti ti-calendar-event"></i>
                                        </span>
                                        <span class="hide-menu ms-2">Tahun Akademik</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ request()->is('tanggal-cetak*') ? 'active' : '' }}">
                                    <a href="{{ route('tanggal-cetak') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-calendar"></i></span>
                                        <span class="hide-menu ms-2">Tanggal Cetak</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item {{ request()->is('master*') ? 'active' : '' }}">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-database me-2"></i>
                                    <span class="hide-menu">Data Master</span>
                                    <i
                                        class="dropdown-icon ti {{ request()->is('master*') ? 'ti-chevron-down' : 'ti-chevron-right' }} me-2"></i>
                                </span>
                            </a>
                            <ul class="collapse first-level ms-4"
                                style="{{ request()->is('master*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('master/siswa*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('santri.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-user"></i></span>
                                        <span class="hide-menu ms-2">Data Siswa</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/guru*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('guru.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-users"></i></span>
                                        <span class="hide-menu ms-2">Data Guru</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/matkul*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('mapel.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-book"></i></span>
                                        <span class="hide-menu ms-2">Mata Pelajaran</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/kelas*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('kelas.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-school"></i></span>
                                        <span class="hide-menu ms-2">Data Kelas</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/user*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('sekolah.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-building"></i></span>
                                        <span class="hide-menu ms-2">Data Sekolah</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/user*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('user.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-user-circle"></i></span>
                                        <span class="hide-menu ms-2">Data Pengguna</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-book-2 me-2"></i>
                                    <span class="hide-menu">Akademik</span>
                                    <i
                                        class="dropdown-icon ti {{ request()->is('rombel*') || request()->is('jadwal*') || request()->is('nilai*') ? 'ti-chevron-down' : 'ti-chevron-right' }} me-2"></i>
                                </span>
                            </a>

                            <ul class="collapse first-level ms-4"
                                style="{{ request()->is('rombel*') || request()->is('jadwal*') || request()->is('nilai*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('rombel*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('rombel.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-users"></i></span>
                                        <span class="hide-menu ms-2">Rombongan Belajar</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('jadwal*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('jadwal.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-calendar-time"></i></span>
                                        <span class="hide-menu ms-2">Jadwal Pelajaran</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('nilai*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('nilai.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-award"></i></span>
                                        <span class="hide-menu ms-2">Nilai</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Menu Pelanggaran --}}
                        <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <span><i class="ti ti-alert-triangle me-2"></i></span>
                                    <span class="hide-menu">Pelanggaran</span>
                                    <i
                                        class="dropdown-icon ti{{ request()->is('pelanggaran*') || request()->is('laporan-pelanggaran*') ? 'ti-chevron-down' : 'ti-chevron-right' }} me-2">
                                    </i>
                                </span>
                            </a>
                            <ul class="collapse first-level ms-4"
                                style="{{ request()->is('pelanggaran*') || request()->is('laporan-pelanggaran*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('pelanggaran') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('pelanggaran.index') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-alert-triangle"></i></span>
                                        <span class="hide-menu ms-2">Data Pelanggaran</span>
                                    </a>
                                </li>
                                {{-- <li
                                    class="sidebar-item {{ request()->is('laporan-pelanggaran*') ? 'active bg-light' : '' }}">
                                    <a href="{{ route('laporan.pelanggaran') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-file-report"></i></span>
                                        <span class="hide-menu ms-2">Laporan Pelanggaran</span>
                                    </a>
                                </li> --}}
                            </ul>
                        </li>


                        {{-- Menu Monitoring (Terpisah) --}}
                        <li class="sidebar-item {{ request()->is('monitoring*') ? 'active bg-light' : '' }}">
                            <a href="#" class="sidebar-link d-flex align-items-center">
                                <span><i class="ti ti-report-search"></i></span>
                                <span class="hide-menu ms-2">Monitoring & Laporan</span>
                            </a>
                        </li>
                    @elseif(auth()->user()->hasRole('guru'))
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item {{ request()->is('/nilai') ? 'active bg-light' : '' }}">
                            <a class="sidebar-link d-flex align-items-center" href="{{ route('nilai.index') }}">
                                <span><i class="ti ti-layout-dashboard"></i></span>
                                <span class="hide-menu ms-2">Dashboard</span>
                            </a>
                        </li>
                        <!-- Menu Guru -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Guru Menu</span>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-award me-2"></i>
                                    <span class="hide-menu">Input Nilai</span>
                                    <i
                                        class="dropdown-icon ti {{ request()->is('nilai/input*') ? 'ti-chevron-down' : 'ti-chevron-right' }} me-2"></i>
                                </span>
                            </a>

                            <ul class="collapse first-level ms-4"
                                style="{{ request()->is('nilai/input*') ? 'display: block;' : 'display: none;' }}">
                                @forelse ($jadwals as $jadwal)
                                    <li
                                        class="sidebar-item {{ request()->is('nilai/input/' . $jadwal->id_jadwal) ? 'active bg-light' : '' }}">
                                        <a href="{{ route('nilai.input', $jadwal->id_jadwal) }}"
                                            class="sidebar-link d-flex align-items-center">
                                            <span><i class="ti ti-list-check"></i></span>
                                            <span class="hide-menu ms-2">
                                                {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }} -
                                                {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                            </span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link d-flex align-items-center text-muted">
                                            <span><i class="ti ti-alert-circle"></i></span>
                                            <span class="hide-menu ms-2">Belum Ada Jadwal</span>
                                        </a>
                                    </li>
                                @endforelse
                            </ul>
                        </li>
                    @else
                        <!-- Menu Santri -->
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Santri Menu</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/profil" aria-expanded="false">
                                <span><i class="ti ti-user"></i></span>
                                <span class="hide-menu">Profil Saya</span>
                            </a>
                        </li>
                    @endif

                    <!-- Menu Logout -->
                    <li class="sidebar-item mt-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="sidebar-link"
                                style="border:none; background:none; width:100%; text-align:left">
                                <span><i class="ti ti-logout"></i></span>
                                <span class="hide-menu">Logout</span>
                            </button>
                        </form>
                    </li>
                @endauth



            </ul>
        </nav>

        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".toggle-dropdown").forEach(function(toggle) {
            toggle.addEventListener("click", function() {
                const dropdown = this.nextElementSibling;
                const icon = this.querySelector(".dropdown-icon");

                if (dropdown.style.display === "none" || dropdown.style.display === "") {
                    dropdown.style.display = "block";
                    icon.classList.remove("ti-chevron-right");
                    icon.classList.add("ti-chevron-down");
                } else {
                    dropdown.style.display = "none";
                    icon.classList.remove("ti-chevron-down");
                    icon.classList.add("ti-chevron-right");
                }
            });
        });
    });
</script>
