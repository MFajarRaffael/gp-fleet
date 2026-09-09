<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GP Fleet</title>

    <link rel="icon" href="{{ asset('images/logoGPI.png') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- GP Fleet CSS -->
    <link rel="stylesheet" href="{{ asset('css/gpfleet.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">

        {{-- =========================================================
        NAVBAR
        ========================================================== --}}
        <nav class="main-header navbar navbar-expand navbar-white navbar-light gp-navbar">

            {{-- Sidebar Toggle --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link gp-menu-toggle" data-widget="pushmenu" href="#" role="button">

                        <i class="fa-solid fa-bars"></i>

                    </a>
                </li>
            </ul>


            {{-- Right Navbar --}}
            <ul class="navbar-nav ml-auto">

                {{-- User --}}
                <li class="nav-item dropdown">

                    <a class="nav-link gp-user-toggle" data-toggle="dropdown" href="#">

                        <span class="gp-user-avatar">
                            <i class="fa-solid fa-user"></i>
                        </span>

                        <span class="gp-user-name">
                            {{ Auth::user()->name }}
                        </span>

                        <i class="fa-solid fa-chevron-down gp-chevron"></i>

                    </a>

                    <div class="dropdown-menu dropdown-menu-right gp-user-menu">

                        <div class="gp-user-header">

                            <div class="gp-profile-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div>
                                <strong>{{ Auth::user()->name }}</strong>

                                <small>
                                    {{ Auth::user()->email }}
                                </small>
                            </div>

                        </div>

                        <div class="dropdown-divider"></div>

                        {{-- Profile --}}
                        <a href="{{ route('profile.edit') }}" class="dropdown-item gp-dropdown-item">

                            <i class="fa-solid fa-user-gear"></i>

                            <span>Profile Settings</span>

                        </a>


                        {{-- Logout --}}
                        <form action="{{ route('logout') }}" method="POST">

                            @csrf

                            <button type="submit" class="dropdown-item gp-dropdown-item gp-logout-item">

                                <i class="fa-solid fa-right-from-bracket"></i>

                                <span>Logout</span>

                            </button>

                        </form>

                    </div>

                </li>

            </ul>

        </nav>


        {{-- =========================================================
        SIDEBAR
        ========================================================== --}}
        <aside class="main-sidebar gp-sidebar elevation-4">

            {{-- Brand --}}
            <a href="{{ route('dashboard') }}" class="brand-link gp-brand">

                <div class="gp-brand-logo">

                    <img src="{{ asset('images/logoGPI.png') }}" alt="Green Planet">

                </div>

                <div class="gp-brand-text">

                    <strong>GP FLEET</strong>

                    <span>Management System</span>

                </div>

            </a>


            <div class="sidebar">

                {{-- User Mini Profile --}}
                <div class="gp-sidebar-user">

                    <div class="gp-sidebar-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div class="gp-sidebar-user-info">

                        <strong>
                            {{ Auth::user()->name }}
                        </strong>

                        <span>
                            Online
                        </span>

                    </div>

                    <span class="gp-online-dot"></span>

                </div>


                {{-- Navigation --}}
                <nav class="mt-3">

                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">


                        {{-- MAIN --}}
                        <li class="nav-header gp-nav-header">
                            MAIN
                        </li>


                        {{-- Dashboard --}}
                        <li class="nav-item">

                            <a href="{{ route('dashboard') }}"
                                class="nav-link gp-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">

                                <i class="nav-icon fa-solid fa-chart-pie"></i>

                                <p>
                                    Dashboard
                                </p>

                            </a>

                        </li>


                        {{-- FLEET --}}
                        <li class="nav-header gp-nav-header">
                            FLEET MANAGEMENT
                        </li>


                        {{-- Kendaraan --}}
                        <li class="nav-item">

                            <a href="{{ route('kendaraan.index') }}"
                                class="nav-link gp-nav-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}">

                                <i class="nav-icon fa-solid fa-car-side"></i>

                                <p>
                                    Kendaraan
                                </p>

                            </a>

                        </li>


                        {{-- Dokumen --}}
                        <li class="nav-item">

                            <a href="{{ route('dokumen.index') }}"
                                class="nav-link gp-nav-link {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">

                                <i class="nav-icon fa-solid fa-file-lines"></i>

                                <p>
                                    Dokumen
                                </p>

                            </a>

                        </li>


                        {{-- Maintenance --}}
                        <li class="nav-item">

                            <a href="#" class="nav-link gp-nav-link">

                                <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>

                                <p>
                                    Maintenance
                                    <span class="right gp-coming-soon">
                                        Soon
                                    </span>
                                </p>

                            </a>

                        </li>


                        {{-- Alat Berat --}}
                        <li class="nav-item">

                            <a href="#" class="nav-link gp-nav-link">

                                <i class="nav-icon fa-solid fa-truck-monster"></i>

                                <p>
                                    Alat Berat
                                    <span class="right gp-coming-soon">
                                        Soon
                                    </span>
                                </p>

                            </a>

                        </li>


                        {{-- SYSTEM --}}
                        <li class="nav-header gp-nav-header">
                            SYSTEM
                        </li>


                        {{-- Profile --}}
                        <li class="nav-item">

                            <a href="{{ route('profile.edit') }}"
                                class="nav-link gp-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">

                                <i class="nav-icon fa-solid fa-user-gear"></i>

                                <p>
                                    Profile Settings
                                </p>

                            </a>

                        </li>

                    </ul>

                </nav>

            </div>

        </aside>


        {{-- =========================================================
        CONTENT
        ========================================================== --}}
        <div class="content-wrapper gp-content-wrapper">

            <section class="content pt-4">

                <div class="container-fluid">

                    @yield('content')

                </div>

            </section>

        </div>


        {{-- =========================================================
        FOOTER
        ========================================================== --}}
        <footer class="main-footer gp-footer">

            <strong>
                GP Fleet
            </strong>

            <span class="mx-1">•</span>

            PT Green Planet Indonesia

            <div class="float-right d-none d-sm-inline">
                © {{ date('Y') }}
            </div>

        </footer>

    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>

</html>