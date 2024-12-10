<!-- resources/views/layouts/app.blade.php -->
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo de la aplicación -->
            <a class="navbar-brand" href="{{ auth()->check() ? url('/home') : url('/') }}">
                <img width="150px" height="auto" src="{{ asset('images/logo/logo_navbar.png') }}" alt="logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <!-- Enlaces solo para usuarios no autenticados -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Iniciar Sesión">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </a>
                        </li>
                    @else
                        <!-- Enlaces solo para usuarios autenticados -->
                        <li class="nav-item">
                            <a class="nav-link" href={{ route('home') }} data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="Inicio">
                                <i class="bi bi-house-door"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vacations.index') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Registro de Vacaciones">
                                <i class="bi bi-calendar3"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('workdays.index') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Horas Diarias">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Perfil">
                                <i class="bi bi-person-circle"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cerrar Sesión">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        </li>
                        <!-- Formulario de cierre de sesión (necesario para hacer logout con seguridad) -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>


    @yield('scripts')

    <!-- Activar tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
</body>

</html>
