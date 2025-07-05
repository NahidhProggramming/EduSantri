<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/logo1.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.min.css ') }}" />
    <link rel="stylesheet" href="css/sidebar.css">
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
    <script src="libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/app.min.js"></script>
    <script src="libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="libs/simplebar/dist/simplebar.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/sidebar.js"></script>

</body>

</html>
