<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - EDU SANTRI</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logos/logo1.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <style>
        body {
            background-color: #13DEB9;
        }

        .login-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .brand-side {
            background-color: #13DEB9;
            color: white;
            padding: 40px;
            text-align: center;
        }

        .brand-side lottie-player {
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
        }

        .login-form {
            padding: 40px;
        }

        .social-icons .btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            margin: 0 5px;
        }

        .btn-primary {
            background-color: #13DEB9;
            border-color: #13DEB9;
        }

        .btn-primary:hover {
            background-color: #10bba2;
            border-color: #10bba2;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-lg-flex login-container">

                    <!-- Brand Side with Lottie -->
                    <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center brand-side">
                        <dotlottie-player
                            src="https://lottie.host/f09f8c1d-a3fc-4a05-b8ef-b84825d749dc/wAXe7rWfds.lottie"
                            background="transparent" speed="1" style="width: 300px; height: 300px" loop
                            autoplay></dotlottie-player>

                        <div class="brand-logo mb-3 fs-4 fw-bold">EDU <strong>SANTRI</strong></div>
                        <p class="fw-bold">Monitoring Santri Kini Lebih Mudah & Cepat</p>
                    </div>

                    <!-- Login Form Side -->
                    <div class="col-lg-6 login-form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Login gagal!</strong>
                                <ul class="mb-0">
                                    <li>Periksa kembali email/username dan password</li>
                                    <li>Pastikan akun Anda aktif</li>
                                    <li>Hubungi admin jika masalah berlanjut</li>
                                </ul>
                            </div>
                        @endif
                        <h4 class="mb-3">Masuk</h4>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="login" class="form-label">Email atau Username</label>
                                <input type="text" class="form-control" id="login" name="login"
                                    placeholder="Email atau Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan kata sandi" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ingat saya</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Masuk Sekarang</button>
                            </div>
                        </form>
                        <hr>
                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
