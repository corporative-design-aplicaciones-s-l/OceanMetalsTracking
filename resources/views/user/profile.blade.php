@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Perfil de Usuario</h2>

        <div class="row">
            <!-- Tarjeta de Datos del Usuario -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Datos del Usuario</span>
                        <!-- Botón para editar -->
                        <button id="editButton" class="btn btn-sm btn-outline-secondary" onclick="toggleEditMode()">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Modo Visualización -->
                        <div id="viewMode">
                            <p><strong>Nombre:</strong> <span id="displayName">{{ $user->name }}</span></p>
                            <p><strong>Correo Electrónico:</strong> <span id="displayEmail">{{ $user->email }}</span></p>
                        </div>
                        <!-- Modo Edición -->
                        <form id="editMode" action="{{ route('profile.update') }}" method="POST" class="d-none">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Campos de Contraseña -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña (dejar en blanco para no
                                    cambiarla)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Calendario -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">Calendario del Mes en Curso</div>
                    <div class="card-body">
                        <!-- Aquí es donde se mostrará el calendario -->
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleEditMode() {
            // Alterna entre el modo de visualización y el modo de edición
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');
            viewMode.classList.toggle('d-none');
            editMode.classList.toggle('d-none');
        }

        // Generar el calendario del mes actual
        function generateCalendar() {
            const calendar = document.getElementById('calendar');
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();

            // Crear el título del mes
            const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre",
                "Octubre", "Noviembre", "Diciembre"
            ];
            const monthTitle = document.createElement('h5');
            monthTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;
            calendar.appendChild(monthTitle);

            // Crear la tabla de días
            const daysOfWeek = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
            const table = document.createElement('table');
            table.className = "table table-bordered";

            // Encabezado de días de la semana
            const headerRow = document.createElement('tr');
            daysOfWeek.forEach(day => {
                const th = document.createElement('th');
                th.textContent = day;
                headerRow.appendChild(th);
            });
            table.appendChild(headerRow);

            // Primer día del mes y número de días en el mes
            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            // Generar las filas del calendario
            let date = 1;
            for (let i = 0; i < 6; i++) { // Máximo 6 filas
                const row = document.createElement('tr');

                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('td');

                    if (i === 0 && j < firstDay) {
                        cell.textContent = ""; // Celdas vacías antes del primer día del mes
                    } else if (date > daysInMonth) {
                        break; // Salir si se pasan los días del mes
                    } else {
                        cell.textContent = date;
                        if (date === today.getDate() && currentMonth === today.getMonth() && currentYear === today
                            .getFullYear()) {
                            cell.classList.add("bg-primary", "text-white"); // Marcar el día actual
                        }
                        date++;
                    }
                    row.appendChild(cell);
                }
                table.appendChild(row);
            }
            calendar.appendChild(table);
        }

        // Llamar a la función para generar el calendario
        document.addEventListener('DOMContentLoaded', generateCalendar);
    </script>
@endsection
