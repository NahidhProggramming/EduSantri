<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - EDU SANTRI</title>
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
                            src="https://lottie.host/15cf5df6-6949-4581-93da-07e56d978943/iRd4E4PqYY.lottie"
                            background="transparent" speed="1" style="width: 300px; height: 300px" loop
                            autoplay></dotlottie-player>
                        <div class="brand-logo mb-3 fs-4 fw-bold">EDU <strong>SANTRI</strong></div>
                        <p class="fw-bold">Yuk buat akun sekarang untuk dapat akses penuh!</p>
                    </div>

                    <!-- Register Form Side -->
                    <div class="col-lg-6 login-form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Registrasi gagal!</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <h4 class="mb-3">Daftar Akun Baru</h4>
                        <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk disini</a></p>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Nama lengkap" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Masukkan kata sandi" required>
                            </div>
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
                                <input type="password" class="form-control" id="password-confirm"
                                    name="password_confirmation" placeholder="Konfirmasi kata sandi" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Verifikasi: {{ generate_captcha() }}</label>
                                <input type="number" class="form-control @error('captcha_answer') is-invalid @enderror"
                                    name="captcha_answer" required>
                                @error('captcha_answer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Pilih Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="guru">Guru</option>
                                    <option value="wali_santri">Wali Santri</option>
                                    <option value="wali_asuh">Wali Asuh</option>
                                </select>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
