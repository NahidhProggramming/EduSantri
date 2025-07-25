<!-- Sidebar Start -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route(auth()->user()->hasRole('admin') ? 'admin.dashboard' : (auth()->user()->hasRole('guru') ? 'nilai.dashboard' : 'kesiswaan.dashboard')) }}"
                class="text-nowrap logo-img">
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


        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <!-- Menu Umum -->

                @auth
                    @if (auth()->user()->hasRole('admin'))
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item {{ request()->is('/dashboard-admin') ? 'active' : '' }}">
                            <a class="sidebar-link d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                <span><i class="ti ti-layout-dashboard"></i></span>
                                <span class="hide-menu ms-2">Dashboard</span>
                            </a>
                        </li>
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
                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('akademik*') ? 'display: block;' : 'display: none;' }}">
                                <li class="sidebar-item {{ request()->is('akademik*') ? 'active' : '' }}">
                                    <a href="/akademik" class="sidebar-link d-flex align-items-center">
                                        <span>
                                            <i class="ti ti-calendar-event"></i>
                                        </span>
                                        <span class="hide-menu">Tahun Akademik</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ request()->is('tanggal-cetak*') ? 'active' : '' }}">
                                    <a href="{{ route('tanggal-cetak') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-calendar"></i></span>
                                        <span class="hide-menu">Tanggal Cetak</span>
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
                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('master*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('master/siswa*') ? 'active' : '' }}">
                                    <a href="{{ route('santri.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-user"></i></span>
                                        <span class="hide-menu">Data Siswa</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/guru*') ? 'active' : '' }}">
                                    <a href="{{ route('guru.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-users"></i></span>
                                        <span class="hide-menu">Data Guru</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/matkul*') ? 'active' : '' }}">
                                    <a href="{{ route('mapel.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-book"></i></span>
                                        <span class="hide-menu">Mata Pelajaran</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/kelas*') ? 'active' : '' }}">
                                    <a href="{{ route('kelas.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-school"></i></span>
                                        <span class="hide-menu">Data Kelas</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/user*') ? 'active' : '' }}">
                                    <a href="{{ route('sekolah.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-building"></i></span>
                                        <span class="hide-menu">Data Sekolah</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('master/user*') ? 'active' : '' }}">
                                    <a href="{{ route('user.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-user-circle"></i></span>
                                        <span class="hide-menu">Data Pengguna</span>
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

                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('rombel*') || request()->is('jadwal*') || request()->is('nilai*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('rombel*') ? 'active' : '' }}">
                                    <a href="{{ route('rombel.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-users"></i></span>
                                        <span class="hide-menu">Rombongan Belajar</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('jadwal*') ? 'active' : '' }}">
                                    <a href="{{ route('jadwal.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-calendar-time"></i></span>
                                        <span class="hide-menu">Jadwal Pelajaran</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('nilai-all') ? 'active' : '' }}">
                                    <a href="{{ route('nilai.index') }}" class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-award"></i></span>
                                        <span class="hide-menu">Nilai</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- Menu Pelanggaran --}}
                        <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <span class="hide-menu">Pelanggaran</span>
                                </span>
                                <i
                                    class="dropdown-icon ti
                                    {{ request()->is('pelanggaran*') || request()->is('laporan-pelanggaran*')
                                        ? 'ti-chevron-down'
                                        : 'ti-chevron-right' }}"></i>
                            </a>

                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('pelanggaran*') || request()->is('laporan-pelanggaran*') ? 'display:block' : '' }}">

                                {{-- Data Pelanggaran --}}
                                <li class="sidebar-item {{ request()->is('pelanggaran') ? 'active' : '' }}">
                                    <a href="{{ route('pelanggaran.index') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <i class="ti ti-alert-triangle"></i>
                                        <span class="hide-menu">Data Pelanggaran</span>
                                    </a>
                                </li>

                                {{-- Laporan Pelanggaran --}}
                                <li class="sidebar-item {{ request()->is('laporan-pelanggaran*') ? 'active' : '' }}">
                                    <a href="{{ route('laporan.pelanggaran') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <i class="ti ti-file-report"></i>
                                        <span class="hide-menu">Laporan Pelanggaran</span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-activity me-2"></i>
                                    <span class="hide-menu">Monitoring</span>
                                    <i
                                        class="dropdown-icon ti
                                        {{ request()->is('monitoring-pelanggaran*') || request()->is('monitoring-akademik*')
                                            ? 'ti-chevron-down'
                                            : 'ti-chevron-right' }} me-2"></i>
                                </span>
                            </a>

                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('monitoring-pelanggaran*') || request()->is('monitoring-akademik*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('monitoring-pelanggaran*') ? 'active' : '' }}">
                                    <a href="{{ route('monitoring.pelanggaran') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-alert-triangle me-2"></i></span>
                                        <span class="hide-menu">Monitoring Pelanggaran</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('monitoring-akademik*') ? 'active' : '' }}">
                                    <a href="{{ route('monitoring.akademik') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-school"></i></span>
                                        <span class="hide-menu">Monitoring Akademik</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @elseif(auth()->user()->hasRole('guru'))
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item {{ request()->is('/nilai') ? 'active' : '' }}">
                            <a class="sidebar-link d-flex align-items-center" href="{{ route('nilai.dashboard') }}">
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

                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('nilai/input*') ? 'display: block;' : 'display: none;' }}">
                                @forelse ($jadwals as $jadwal)
                                    <li
                                        class="sidebar-item {{ request()->is('nilai/input/' . $jadwal->id_jadwal) ? 'active' : '' }}">
                                        <a href="{{ route('nilai.input', $jadwal->id_jadwal) }}"
                                            class="sidebar-link d-flex align-items-center">
                                            <span><i class="ti ti-list-check"></i></span>
                                            <span class="hide-menu">
                                                {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }} -
                                                {{ $jadwal->kelas->nama_kelas ?? '-' }}
                                            </span>
                                        </a>
                                    </li>
                                @empty
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link d-flex align-items-center text-muted">
                                            <span><i class="ti ti-alert-circle"></i></span>
                                            <span class="hide-menu">Belum Ada Jadwal</span>
                                        </a>
                                    </li>
                                @endforelse
                            </ul>
                        </li>
                        @php
                            $guru = \App\Models\Guru::where('user_id', auth()->id())->first();
                            $isWaliKelas = false;

                            if ($guru) {
                                $isWaliKelas = \App\Models\Kelas::where('wali_kelas_id', $guru->id_guru)->exists();
                            }
                        @endphp

                        @if ($isWaliKelas)
                            <li class="sidebar-item {{ request()->is('pelanggaran') ? 'active' : '' }}">
                                <a href="{{ route('pelanggaran.index') }}"
                                    class="sidebar-link d-flex align-items-center">
                                    <span><i class="ti ti-check"></i></span>
                                    <span class="hide-menu ms-2">Verifikasi Pelanggaran</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                            <a class="sidebar-link d-flex align-items-center justify-between toggle-dropdown"
                                href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-activity me-2"></i>
                                    <span class="hide-menu">Monitoring</span>
                                    <i
                                        class="dropdown-icon ti
                                        {{ request()->is('monitoring-pelanggaran*') || request()->is('monitoring-akademik*')
                                            ? 'ti-chevron-down'
                                            : 'ti-chevron-right' }} me-2"></i>
                                </span>
                            </a>

                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('monitoring-pelanggaran*') || request()->is('monitoring-akademik*') ? 'display: block;' : 'display: none;' }}">

                                <li class="sidebar-item {{ request()->is('monitoring-pelanggaran*') ? 'active' : '' }}">
                                    <a href="{{ route('monitoring.pelanggaran') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-alert-triangle me-2"></i></span>
                                        <span class="hide-menu">Monitoring Pelanggaran</span>
                                    </a>
                                </li>

                                <li class="sidebar-item {{ request()->is('monitoring-akademik*') ? 'active' : '' }}">
                                    <a href="{{ route('monitoring.akademik') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <span><i class="ti ti-school"></i></span>
                                        <span class="hide-menu">Monitoring Akademik</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    @elseif(auth()->user()->hasRole('kesiswaan'))
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item {{ request()->is('dashboard-kesiswaan') ? 'active' : '' }}">

                            <a class="sidebar-link d-flex align-items-center" href="{{ route('kesiswaan.dashboard') }}">
                                <span><i class="ti ti-layout-dashboard"></i></span>
                                <span class="hide-menu ms-2">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Menu</span>
                        </li>
                        <li class="sidebar-item">
                            {{-- ===== HEADER DROPDOWN ===== --}}
                            <a class="sidebar-link d-flex align-items-center toggle-dropdown" href="javascript:void(0);">
                                <span class="d-flex align-items-center">
                                    <i class="ti ti-alert-triangle me-2"></i>
                                    <span class="hide-menu">Pelanggaran</span>
                                </span>
                                <i
                                    class="dropdown-icon ti
                                    {{ request()->is('pelanggaran*', 'jenis-pelanggaran*') ? 'ti-chevron-down' : 'ti-chevron-right' }}
                                    ms-auto"></i>
                            </a>

                            {{-- ===== SUB‑MENU ===== --}}
                            <ul class="collapse first-level ms-2"
                                style="{{ request()->is('pelanggaran*', 'jenis-pelanggaran*') ? 'display:block' : '' }}">

                                {{-- Data Pelanggaran --}}
                                <li class="sidebar-item {{ request()->is('pelanggaran') ? 'active' : '' }}">
                                    <a href="{{ route('pelanggaran.index') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <i class="ti ti-alert-triangle"></i>
                                        <span class="hide-menu">Data Pelanggaran</span>
                                    </a>
                                </li>

                                {{-- Jenis Pelanggaran (baru) --}}
                                <li class="sidebar-item {{ request()->is('jenis-pelanggaran*') ? 'active' : '' }}">
                                    <a href="{{ route('jenis.pelanggaran') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <i class="ti ti-list-details"></i>
                                        <span class="hide-menu">Jenis Pelanggaran</span>
                                    </a>
                                </li>
                                <li class="sidebar-item {{ request()->is('laporan-pelanggaran*') ? 'active' : '' }}">
                                    <a href="{{ route('laporan.pelanggaran') }}"
                                        class="sidebar-link d-flex align-items-center">
                                        <i class="ti ti-file-report"></i>
                                        <span class="hide-menu">Laporan Pelanggaran</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item {{ request()->is('monitoring-pelanggaran*') ? 'active' : '' }}">
                            <a href="{{ route('monitoring.pelanggaran') }}"
                                class="sidebar-link d-flex align-items-center">
                                <span><i class="ti ti-alert-triangle me-2"></i></span>
                                <span class="hide-menu">Monitoring Pelanggaran</span>
                            </a>
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
                            <button type="submit" class="sidebar-link d-flex align-items-center px-3 py-2 w-100"
                                style="border: none; background: none; text-align: left;">
                                <span><i class="ti ti-logout"></i></span>
                                <span class="hide-menu ms-2">Logout</span>
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
