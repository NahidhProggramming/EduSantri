<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/logo1.png') }}" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <style>
        .custom-primary {
            background-color: #13DEB9 !important;
            color: white;
        }

        .custom-button {
            background-color: #13DEB9;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
        }

        .custom-button:hover {
            background-color: #10c9a9;
            color: white;
        }

        .icon-box {
            background-color: white;
            border: 1px solid #eee;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            height: 100%;
            transition: 0.3s;
        }

        .highlight-box {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 30px;
        }

        .bg-hero {
            background: url('/images/bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            position: relative;
        }

        .bg-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .bg-hero>* {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body>
    <!-- Auth Navbar Component -->
    <x-auth-navbar active="home" />

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-4 mt-5">
        <small>&copy; {{ date('Y') }} Edu Santri. All rights reserved.</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>
