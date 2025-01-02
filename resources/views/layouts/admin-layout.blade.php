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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>

    <title>{{ config('app.name', 'Tick Track') }}</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">

    <!-- Vite and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body>
    <!-- Contenedor de alertas flotantes -->
    <div id="alertContainer" class="floating-alert-container position-fixed top-0 end-0 p-3" style="z-index: 1055;"></div>

    <!-- resources/views/layouts/admin-layout.blade.php -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Izquierdo -->
            <div class="col-md-1 bg-dark sidebar text-center position-sticky" style="height: calc(100vh);">
                <a class="navbar-brand" href="{{ auth()->check() ? url('/admin/dashboard') : url('/') }}">
                    <img width="100%" height="auto" class="mt-2" src="{{ asset('images/logo/logo_navbar.png') }}"
                        alt="logo">
                </a>
                <ul class="nav flex-column text-light mt-2 text-center justify-between">
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.dashboard') }}" class="btn btn-info btn-sm"
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Panel principal">
                            <i class="bi bi-speedometer2" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.create_worker') }}"
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Nuevo trabajador">
                            <i class="bi bi-person-plus-fill" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.workers.index') }}"
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Lista de trabajadores">
                            <i class="bi bi-list-ul" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('admin.vacations') }}" data-bs-toggle="tooltip"
                            data-bs-placement="right" title="Administrar vacaciones">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('profile.show') }}" data-bs-toggle="tooltip"
                            data-bs-placement="right" title="Ver/editar perfil">
                            <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                        </a>
                    </li>
                    {{-- <li class="nav-item mb-0">
                    <a class="nav-link text-light" href="#" data-bs-toggle="tooltip" data-bs-placement="right"
                        title="Configuraciones">
                        <i class="bi bi-gear-fill" style="font-size: 2rem;"></i>
                    </a>
                </li> --}}
                    <li class="nav-item mb-0">
                        <a class="nav-link text-light" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            data-bs-toggle="tooltip" data-bs-placement="right" title="Salir">
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
