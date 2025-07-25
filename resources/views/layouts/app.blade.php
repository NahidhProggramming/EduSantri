<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/logo1.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.min.css ') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"> --}}
    <style>
        /* Warna dasar sidebar */
        .left-sidebar,
        .scroll-sidebar {
            background-color: #ffffff !important;
        }

        /* Semua link sidebar */
        .sidebar-link {
            background-color: #ffffff !important;
            color: #000000 !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar-link.active {
            background-color: #13DEB9 !important;
            color: #ffffff !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        /* Hover efek */
        .sidebar-item:hover>.sidebar-link:not(.active) {
            background-color: #13DEB9 !important;
            color: #ffffff !important;
        }

        /* Untuk item yang memiliki dropdown, saat hover link utama */
        .sidebar-item.has-dropdown:hover>.sidebar-link {
            background-color: #ffffff !important;
            color: #13DEB9 !important;
        }

        /* Active state: hanya menu aktif yang putih */
        .sidebar-item.active>.sidebar-link {
            background-color: #13DEB9 !important;
            color: #ffffff !important;
            font-weight: 600;
        }

        /* Ikon di sidebar */
        .sidebar-link i {
            color: #020202 !important;
            margin-right: 10px;
            transition: 0.3s;
        }

        .sidebar-link.active i {
            color: #ffffff !important;
        }

        /* Saat hover, ikon pada link yang tidak aktif berubah menjadi putih */
        .sidebar-item:hover>.sidebar-link:not(.active) i {
            color: #ffffff !important;
        }

        /* Ikon saat menu aktif */
        .sidebar-item.active>.sidebar-link i {
            color: #ffffff !important;
        }

        /* Dropdown menu */
        .dropdown-menu {
            padding-left: 20px;
            list-style: none;
            display: none;
        }

        .sidebar-item.has-dropdown.active>.dropdown-menu {
            display: block;
        }

        /* Untuk dropdown yang terbuka, kita tambahkan class active di parent */
        .sidebar-item.has-dropdown.active>.sidebar-link {
            background-color: #ffffff !important;
            color: #13DEB9 !important;
        }

        /* Tambahkan panah yang bisa di-toggle */
        .dropdown-toggle {
            margin-left: auto;
            transition: transform 0.3s;
        }

        .sidebar-item.has-dropdown.active>.sidebar-link>.dropdown-toggle {
            transform: rotate(180deg);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <x-navbar></x-navbar>
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <x-header></x-header>
            @yield('content')
        </div>

    </div>
    <script src="libs/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/app.min.js"></script>
    <script src="libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="libs/simplebar/dist/simplebar.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/sidebar.js"></script>
    @stack('scripts')
</body>

</html>
