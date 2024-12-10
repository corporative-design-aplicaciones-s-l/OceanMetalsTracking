<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin-layout')

@section('content')
    <h1 class="my-4">Dashboard de Administración</h1>

    <div class="row">
        <!-- Columna Izquierda: Estado de Trabajadores, Solicitudes de Vacaciones y Información por Trabajador -->
        <div class="col-md-8">
            <!-- Card con Pie-Chart de Estado de Trabajadores -->
            <div class="card mb-4">
                <div class="card-header">Estado de Trabajadores</div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="workerStatusChart" style="max-width: 300px; max-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Card con Solicitudes de Vacaciones -->
            <div class="card mb-4">
                <div class="card-header">Solicitudes de Vacaciones</div>
                <div class="card-body">
                    <ul id="vacationRequestsList" class="list-group list-group-flush">
                        @forelse ($vacationRequests as $request)
                            <li class="list-group-item">
                                {{ $request->user->name }} {{ $request->user->last_name }} -
                                {{ $request->start_date }} al {{ $request->end_date }}
                            </li>
                        @empty
                            <li class="list-group-item text-center">No hay solicitudes de vacaciones pendientes.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Card de Información Desglosada por Trabajador -->
            <div class="card">
                <div class="card-header">Información por Trabajador</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Vacaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workerDetails as $detail)
                                <tr>
                                    <td>{{ $detail['name'] }}</td>
                                    <td>
                                        @switch($detail['estado'])
                                            @case('trabajando')
                                                <span class="badge bg-success">Trabajando</span>
                                            @break

                                            @case('no_trabajando')
                                                <span class="badge bg-danger">Sin trabajar</span>
                                            @break

                                            @case('descansando')
                                                <span class="badge bg-secondary">Descansando</span>
                                            @break

                                            @case('de_vacaciones')
                                                <span class="badge bg-info">De vacaciones</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>{{ $detail['vacations_left'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Información Detallada de Trabajadores -->
        <div class="col-md-4">
            <div class="card mb-4 h-100">
                <div class="card-header">Información Detallada</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Trabajando -->
                        <table class="table table-success table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Trabajando</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workerStatuses['trabajando'] as $worker)
                                    <tr>
                                        <td>{{ $worker->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">No hay trabajadores trabajando actualmente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- No trabajando -->
                        <table class="table table-danger table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Sin trabajar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workerStatuses['no_trabajando'] as $worker)
                                    <tr>
                                        <td>{{ $worker->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">No hay trabajadores sin trabajar actualmente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Descansando -->
                        <table class="table table-secondary table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">Descansando</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workerStatuses['descansando'] as $worker)
                                    <tr>
                                        <td>{{ $worker->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">No hay trabajadores descansando actualmente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- De vacaciones -->
                        <table class="table table-info table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">De Vacaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workerStatuses['de_vacaciones'] as $worker)
                                    <tr>
                                        <td>{{ $worker->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center">No hay trabajadores de vacaciones actualmente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuración del gráfico Pie-Chart con Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('workerStatusChart').getContext('2d');
            var workerStatusChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Trabajando', 'No trabajando', 'Descansando', 'De vacaciones'],
                    datasets: [{
                        data: [
                            {{ $chartData['trabajando'] }},
                            {{ $chartData['no_trabajando'] }},
                            {{ $chartData['descansando'] }},
                            {{ $chartData['de_vacaciones'] }}
                        ],
                        backgroundColor: ['#28a745', '#dc3545', '#6c757d', '#007bff']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

        });
    </script>
@endsection
