<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GP Fleet | Register</title>

    <link rel="icon" href="{{ asset('images/logoGPI.png') }}">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet"
        href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('css/gpfleet.css') }}">
</head>

<body class="gp-login-page">

    <div class="gp-login-overlay"></div>

    <div class="login-wrapper">

        <div class="login-card">

            <img src="{{ asset('images/logoGPI.png') }}"
                class="login-logo"
                alt="Green Planet">

            <div class="text-center mb-4">
                <h4 class="font-weight-bold mb-1">Create Account</h4>
                <small class="text-muted">
                    Register your GP Fleet account
                </small>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="gp-input-group">
                    <i class="fa-solid fa-user"></i>

                    <input
                        type="text"
                        name="name"
                        placeholder="Name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name">
                </div>

                @error('name')
                    <small class="text-danger d-block mb-2">
                        {{ $message }}
                    </small>
                @enderror

                {{-- Email --}}
                <div class="gp-input-group">
                    <i class="fa-solid fa-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username">
                </div>

                @error('email')
                    <small class="text-danger d-block mb-2">
                        {{ $message }}
                    </small>
                @enderror

                {{-- Password --}}
                <div class="gp-input-group">
                    <i class="fa-solid fa-lock"></i>

                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                        autocomplete="new-password">
                </div>

                @error('password')
                    <small class="text-danger d-block mb-2">
                        {{ $message }}
                    </small>
                @enderror

                {{-- Confirm Password --}}
                <div class="gp-input-group">
                    <i class="fa-solid fa-shield-halved"></i>

                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        required
                        autocomplete="new-password">
                </div>

                @error('password_confirmation')
                    <small class="text-danger d-block mb-2">
                        {{ $message }}
                    </small>
                @enderror

                <button class="login-btn" type="submit">
                    <i class="fa-solid fa-user-plus mr-2"></i>
                    Create Account
                </button>

            </form>

            <div class="text-center mt-3">
                <span class="text-muted">
                    Already have an account?
                </span>

                <a href="{{ route('login') }}"
                    class="font-weight-bold gp-register-link">
                    Login
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