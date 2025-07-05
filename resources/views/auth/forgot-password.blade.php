<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - EDU SANTRI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
    <style>
        body {
            background-color: #13DEB9;
        }

        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 1000px;
            margin: 2rem auto;
        }

        .brand-side {
            background-color: #13DEB9;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .form-side {
            padding: 3rem;
        }

        .brand-logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 1rem 0;
        }

        .btn-primary {
            background-color: #13DEB9;
            border-color: #13DEB9;
            padding: 0.5rem 1.5rem;
        }

        .btn-primary:hover {
            background-color: #10bba2;
            border-color: #10bba2;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="auth-container d-lg-flex">
            <!-- Brand Side -->
            <div class="col-lg-6 brand-side">
                <dotlottie-player src="https://lottie.host/8adac99e-f6ae-4e07-adc4-aa647192554d/nS1aGNAg6X.lottie"
                    background="transparent" speed="1" style="width: 250px; height: 250px;" loop
                    autoplay></dotlottie-player>
                <div class="brand-logo">EDU <strong>SANTRI</strong></div>
                <p class="fw-bold">Lupa password nih?</p>
            </div>

            <!-- Form Side -->
            <div class="col-lg-6 form-side">
                <h4 class="mb-4">Reset Password</h4>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary mb-2">
                            Kirim Link Reset Password
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
