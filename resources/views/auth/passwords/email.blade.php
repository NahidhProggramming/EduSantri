@extends('layouts.auth')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-lg-flex login-container">
                <!-- Brand Side -->
                <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center brand-side">
                    <dotlottie-player
                        src="https://lottie.host/f09f8c1d-a3fc-4a05-b8ef-b84825d749dc/wAXe7rWfds.lottie"
                        background="transparent" speed="1" style="width: 300px; height: 300px" loop
                        autoplay></dotlottie-player>
                    <div class="brand-logo mb-3 fs-4 fw-bold">EDU <strong>SANTRI</strong></div>
                    <p class="fw-bold">Monitoring Santri Kini Lebih Mudah & Cepat</p>
                </div>
            <div class="card">
=======
                <!-- Form Side -->
                <div class="col-lg-6 login-form">
                    <div class="card">
=======
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
