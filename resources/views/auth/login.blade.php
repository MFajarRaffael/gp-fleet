<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GP Fleet | Login</title>

    <link rel="icon" href="{{ asset('images/logoGPI.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gpfleet.css') }}">
</head>

<body class="gp-login-page">

    <div class="gp-login-overlay"></div>

    <div class="login-wrapper">

        <div class="login-card">

            <img src="{{ asset('images/logoGPI.png') }}" class="login-logo" alt="Green Planet">

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="gp-input-group">
                    <i class="fa-solid fa-envelope"></i>

                    <input type="email" name="email" placeholder="Email     " value="{{ old('email') }}" required>
                </div>

                @error('email')
                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror

                <div class="gp-input-group">
                    <i class="fa-solid fa-lock"></i>

                    <input type="password" name="password" placeholder="Password" required>
                </div>

                @error('password')
                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                @enderror

                <div class="login-option">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember Me
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <button class="login-btn" type="submit">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>
                    Login
                </button>

            </form>

            <div class="text-center mt-3">
                <span class="text-muted">
                    Don't have an account?
                </span>
            
                @if(Route::has('register'))
                    <a href="{{ route('register') }}" class="font-weight-bold gp-register-link">
                        Register
                    </a>
                @endif
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