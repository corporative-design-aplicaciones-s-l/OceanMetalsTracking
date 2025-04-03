<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
        <link rel="manifest" href="/manifest.json" />


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
    <!-- Contenedor de alertas flotantes -->
    <div id="alertContainer" class="floating-alert-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

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
                                <i class="bi bi-box-arrow-in-right nav-icon"></i><span class="sidebar-text ms-2">Iniciar Sesion</span> <!-- Solo visible en móvil -->
                            </a>
                        </li>
                    @else
                        <!-- Enlaces solo para usuarios autenticados -->
                        <li class="nav-item">
                            <a class="nav-link" href={{ route('home') }} data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="Inicio">
                                <i class="bi bi-house-door nav-icon"></i><span class="sidebar-text ms-2">Inicio</span> <!-- Solo visible en móvil -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vacations.index') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Registro de Vacaciones">
                                <i class="bi bi-calendar3 nav-icon"></i><span class="sidebar-text ms-2">Registro de Vacaciones</span> <!-- Solo visible en móvil -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('workdays.index') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Horas Diarias">
                                <i class="bi bi-clock-history nav-icon"></i><span class="sidebar-text ms-2">Horas Diarias</span> <!-- Solo visible en móvil -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.show') }}" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" title="Perfil">
                                <i class="bi bi-person-circle nav-icon"></i><span class="sidebar-text ms-2">Perfil</span> <!-- Solo visible en móvil -->
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cerrar Sesión">
                                <i class="bi bi-box-arrow-right nav-icon"></i><span class="sidebar-text ms-2">Cerrar Sesión</span> <!-- Solo visible en móvil -->
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

    <script>
        // Activar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });

        document.addEventListener('DOMContentLoaded', () => {
            const alertContainer = document.getElementById('alertContainer');

            // Cargar mensajes desde el backend
            const status = "{{ session('status') }}";
            const message = "{{ session('message') }}";

            if (status && message) {
                createAlert(status, message);
            }

            function createAlert(type, message) {
                const alert = document.createElement('div');
                alert.className = `floating-alert alert alert-${type}`;
                alert.innerHTML = `
            <span>${message}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        `;

                alertContainer.appendChild(alert);

                // Eliminar la alerta automáticamente después de 5 segundos
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }
        });
    </script>
</body>

</html>
