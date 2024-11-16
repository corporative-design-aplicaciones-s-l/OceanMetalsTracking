<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <!-- Incluir Bootstrap JS y sus dependencias (Popper) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <title>{{ config('app.name', 'Tick Track') }}</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    <!-- Vite and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body>
    <!-- resources/views/layouts/admin-layout.blade.php -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Izquierdo -->
            <div class="col-md-1 bg-dark sidebar text-center position-sticky" style="height: calc(100vh);">
                <a class="navbar-brand" href="{{ auth()->check() ? url('/home') : url('/') }}">
                    <img width="100%" height="auto" class="mt-2" src="{{ asset('images/logo/logo_navbar.png') }}"
                        alt="logo">
                </a>
                <ul class="nav flex-column text-light mt-2 text-center justify-between">
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.create_worker') }}">
                            <i class="bi bi-person-plus-fill" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.workers.index') }}">
                            <i class="bi bi-list-ul" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.vacations') }}">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="#">
                            <i class="bi bi-gear-fill" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('profile.show') }}">
                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right" style="font-size: 2rem;"></i>
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                </ul>
            </div>

            <!-- Contenido Principal -->
            <div class="col-md-11 py-4" style="overflow-y: auto; max-height: calc(100vh - 56px);">
                @yield('content')
            </div>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
