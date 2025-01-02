@extends('layouts.admin-layout')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Administrar Vacaciones</h2>

        <!-- Tabla 1: Solicitud de Vacaciones -->
        <div class="card mb-4 bg-dark text-white">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Solicitudes de Vacaciones
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Días Totales</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingVacations as $vacation)
                                <tr>
                                    <td>{{ $vacation->user->name }}</td>
                                    <td>{{ $vacation->user->last_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vacation->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vacation->end_date)->format('d/m/Y') }}</td>
                                    <td>{{ $vacation->total_days }}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm"
                                            onclick="validateVacation({{ $vacation->id }})">
                                            Aceptar
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="declineVacation({{ $vacation->id }})">
                                            Rechazar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay solicitudes pendientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabla 2: Vacaciones Futuras -->
        <div class="card mb-4 bg-dark text-white">
            <div class="card-header">
                <i class="bi bi-calendar3"></i> Vacaciones Futuras
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Días Hasta Vacaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($futureVacations as $vacation)
                                <tr>
                                    <td>{{ $vacation->user->name }}</td>
                                    <td>{{ $vacation->user->last_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vacation->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vacation->end_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($vacation->start_date)->startOfDay()) }}
                                        días</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay vacaciones futuras programadas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabla 3: Empleados de Vacaciones -->
        <div class="card bg-dark text-white">
            <div class="card-header">
                <i class="bi bi-person-fill"></i> Empleados de Vacaciones
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Días Hasta Fin de Vacaciones</th>
                                <th>Fecha Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employeesOnVacation as $vacation)
                                <tr>
                                    <td>{{ $vacation->user->name }}</td>
                                    <td>{{ $vacation->user->last_name }}</td>
                                    <td>{{ \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($vacation->end_date)) }}
                                        días</td>
                                    <td>{{ \Carbon\Carbon::parse($vacation->end_date)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay empleados actualmente de vacaciones.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function validateVacation(vacationId) {
            if (confirm('¿Estás seguro de aceptar esta solicitud de vacaciones?')) {
                fetch(`/admin/vacations/${vacationId}/validate`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                }).then(() => location.reload());
            }
        }

        function declineVacation(vacationId) {
            if (confirm('¿Estás seguro de rechazar esta solicitud de vacaciones?')) {
                fetch(`/admin/vacations/${vacationId}/decline`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                }).then(() => location.reload());
            }
        }
    </script>
@endsection
