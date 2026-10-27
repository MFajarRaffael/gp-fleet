<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- IFrame Preloader Removal Workaround --}}
    <!-- IFrame Preloader Removal Workaround -->
    <style type="text/css">
        body.iframe-mode .preloader {
            display: none !important;
        }
    </style>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_css_path', 'css/app.css')) }}">
            @break

            @case('vite')
                @vite([config('adminlte.laravel_css_path', 'resources/css/app.css'), config('adminlte.laravel_js_path', 'resources/js/app.js')])
            @break

            @case('vite_js_only')
                @vite(config('adminlte.laravel_js_path', 'resources/js/app.js'))
            @break

            @default
                <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
                <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

                @if(config('adminlte.google_fonts.allowed', true))
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
                @endif
        @endswitch
    @endif

    {{-- Extra Configured Plugins Stylesheets --}}
    @include('adminlte::plugins', ['type' => 'css'])

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicons/ms-icon-144x144.png') }}">
    @endif

    <link rel="icon" type="image/png" href="{{ asset('images/gpi_logo.png') }}">

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts (depends on Laravel asset bundling tool) --}}
    @if(config('adminlte.enabled_laravel_mix', false))
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @else
        @switch(config('adminlte.laravel_asset_bundling', false))
            @case('mix')
                <script src="{{ mix(config('adminlte.laravel_js_path', 'js/app.js')) }}"></script>
            @break

            @case('vite')
            @case('vite_js_only')
            @break

            @default
                <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
                <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
                <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
        @endswitch
    @endif

    {{-- Extra Configured Plugins Scripts --}}
    @include('adminlte::plugins', ['type' => 'js'])

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(intval(app()->version()) >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    
<style>
    #gp-push-notification-button .nav-link {
        cursor: pointer;
    }

    #gp-push-notification-button .nav-icon {
        color: #9ca3af;
        transition: color .2s ease;
    }

    #gp-push-notification-button.gp-push-enabled .nav-icon {
        color: #16a34a !important;
    }

    #gp-push-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: none;
        max-width: 320px;
        padding: 12px 16px;
        background: #fff;
        border-left: 4px solid #16a34a;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,.15);
        color: #333;
    }

    #gp-push-toast.gp-push-error {
        border-left-color: #dc3545;
    }

    #gp-push-toast strong {
        display: block;
        margin-bottom: 3px;
    }
</style>

<div id="gp-push-toast" role="status" aria-live="polite">
    <strong>GP Fleet</strong>
    <span id="gp-push-toast-message"></span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('gp-push-notification-button');
    if (!button) return;

    const canUsePushNotifications = @json(
        auth()->check() && auth()->user()->isAdmin()
    );

    if (!canUsePushNotifications) {
        button.remove();
        return;
    }

    const publicKey = @json(config('webpush.vapid.public_key'));
    const toast = document.getElementById('gp-push-toast');
    const toastMessage = document.getElementById('gp-push-toast-message');
    let toastTimer;

    function showToast(message, isError = false) {
        toastMessage.textContent = message;
        toast.classList.toggle('gp-push-error', isError);
        toast.style.display = 'block';

        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => {
            toast.style.display = 'none';
        }, 3500);
    }

    function setEnabled(enabled) {
        button.classList.toggle('gp-push-enabled', enabled);

        const link = button.querySelector('a');
        const icon = button.querySelector('i');

        if (link) {
            link.title = enabled
                ? 'Notifikasi aktif'
                : 'Aktifkan notifikasi';
            link.setAttribute('aria-label', link.title);
        }

        if (icon) {
            icon.style.setProperty(
                'color',
                enabled ? '#16a34a' : '#9ca3af',
                'important'
            );
        }
    }

    function decodeVapidKey(key) {
        const padding = '='.repeat((4 - key.length % 4) % 4);
        const base64 = (key + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const raw = atob(base64);
        return Uint8Array.from(raw, char => char.charCodeAt(0));
    }

    async function getRegistration() {
        return navigator.serviceWorker.register('/sw.js');
    }

    // Pertahankan warna hijau setelah halaman dimuat ulang.
    async function checkExistingSubscription() {
        try {
            const response = await fetch(@json(route('push-subscriptions.status')), {
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                setEnabled(false);
                return;
            }

            const data = await response.json();
            setEnabled(data.enabled === true);
        } catch (error) {
            console.error('Gagal memeriksa status notifikasi:', error);
            setEnabled(false);
        }
    }

    checkExistingSubscription();

    button.addEventListener('click', async function (event) {
        event.preventDefault();

        try {
            if (!('serviceWorker' in navigator) ||
                !('PushManager' in window) ||
                !('Notification' in window)) {
                showToast('Browser ini belum mendukung notifikasi push.', true);
                return;
            }

            if (!publicKey) {
                showToast('Konfigurasi notifikasi belum lengkap.', true);
                return;
            }

            if (Notification.permission === 'denied') {
                showToast(
                    'Izin notifikasi diblokir. Aktifkan melalui pengaturan browser.',
                    true
                );
                return;
            }

            const permission = await Notification.requestPermission();

            if (permission !== 'granted') {
                showToast('Izin notifikasi belum diberikan.', true);
                return;
            }

            const registration = await getRegistration();
            let subscription =
                await registration.pushManager.getSubscription();

            if (!subscription) {
                subscription = await registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: decodeVapidKey(publicKey)
                });
            }

            const response = await fetch(
                @json(route('push-subscriptions.store')),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify(subscription)
                }
            );

            if (!response.ok) {
                throw new Error('Gagal menyimpan langganan browser.');
            }

            setEnabled(true);
            showToast('Notifikasi browser berhasil diaktifkan.');
        } catch (error) {
            console.error('Web Push error:', error);
            showToast('Notifikasi gagal diaktifkan. Silakan coba lagi.', true);
        }
    });
});
</script>


</body>

</html>
