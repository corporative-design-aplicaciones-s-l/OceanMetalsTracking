<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <!-- Incluir Bootstrap JS y sus dependencias (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <title>{{ config('app.name', 'Tick Track') }}</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    <!-- Vite and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')

</head>

<body>
    <!-- Contenedor de alertas flotantes -->
    <div id="alertContainer" class="floating-alert-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <!-- Navbar para dispositivos móviles -->
    <nav class="navbar navbar-dark bg-dark d-md-none">
        <div class="container-fluid">
            <a class="navbar-brand ms-2" href="{{ url('/') }}">
                <img width="150px" height="auto" src="{{ asset('images/logo/logo_navbar.png') }}" alt="logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">

        <div class="row">
            <!-- Sidebar Izquierdo -->
            <div class="col-md-1 bg-dark sidebar text-center collapse d-md-block align-items-center" id="sidebarMenu"
                style="height: calc(100vh);">
                <a class="navbar-brand sidebar-logo" href="{{ auth()->check() ? url('/admin/dashboard') : url('/') }}">
                    <img width="100%" height="auto" class="mt-2" src="{{ asset('images/logo/logo_navbar.png') }}"
                        alt="logo">
                </a>
                <ul class="nav flex-column text-light mt-2 text-center">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-light d-flex align-items-center "
                            href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 sidebar-icon"></i>
                            <span class="sidebar-text ms-2">Dashboard</span> <!-- Solo visible en móvil -->
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-light d-flex align-items-center "
                            href="{{ route('admin.create_worker') }}">
                            <i class="bi bi-person-plus-fill sidebar-icon"></i>
                            <span class="sidebar-text ms-2">Nuevo Trabajador</span> <!-- Solo visible en móvil -->
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-light d-flex align-items-center "
                            href="{{ route('admin.workers.index') }}">
                            <i class="bi bi-list-ul sidebar-icon"></i>
                            <span class="sidebar-text ms-2">Trabajadores</span> <!-- Solo visible en móvil -->
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-light d-flex align-items-center "
                            href="{{ route('admin.vacations') }}">
                            <i class="bi bi-calendar-check sidebar-icon"></i>
                            <span class="sidebar-text ms-2">Vacaciones</span> <!-- Solo visible en móvil -->
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-light d-flex align-items-center "
                            href="{{ route('profile.show') }}">
                            <i class="bi bi-person-circle sidebar-icon"></i>
                            <span class="sidebar-text ms-2">Perfil</span> <!-- Solo visible en móvil -->
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-light d-flex align-items-center "
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right sidebar-icon"></i>
                            <span class="sidebar-text ms-2">Salir</span> <!-- Solo visible en móvil -->
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

    <script>
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
