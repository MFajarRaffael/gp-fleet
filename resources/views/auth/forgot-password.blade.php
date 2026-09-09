<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GP Fleet | Forgot Password</title>

    <link rel="icon" href="{{ asset('images/logoGPI.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/gpfleet.css') }}">
</head>

<body class="gp-login-page">

    <div class="gp-login-overlay"></div>

    <div class="login-wrapper">

        <div class="login-card">

            <img src="{{ asset('images/logoGPI.png') }}" class="login-logo" alt="Green Planet">

            <div class="text-center mb-4">
                <h4 class="font-weight-bold mb-1">
                    Forgot Password?
                </h4>

                <small class="text-muted">
                    Enter your email to receive a password reset link.
                </small>
            </div>

            @if (session('status'))
                <div class="alert alert-success text-center">
                    <i class="fa-solid fa-circle-check mr-1"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">

                @csrf

                <div class="gp-input-group">
                    <i class="fa-solid fa-envelope"></i>

                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus
                        autocomplete="email">
                </div>

                @error('email')
                    <small class="text-danger d-block mb-2">
                        {{ $message }}
                    </small>
                @enderror

                <button class="login-btn" type="submit">
                    <i class="fa-solid fa-paper-plane mr-2"></i>
                    Send Reset Link
                </button>

            </form>

            <div class="text-center mt-3">

                <a href="{{ route('login') }}" class="font-weight-bold gp-register-link">

                    <i class="fa-solid fa-arrow-left mr-1"></i>
                    Back to Login

                </a>

            </div>

            <div class="login-footer">
                <strong>PT Green Planet Indonesia</strong>
                <br>
                © {{ date('Y') }} GP Fleet Management System
            </div>

        </div>

    </div>

</body>

</html>