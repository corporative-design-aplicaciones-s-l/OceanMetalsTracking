<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin-layout')

@section('admin-content')
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
                        <li class="list-group-item">Juan Pérez - 15/11/2024 al 20/11/2024</li>
                        <!-- Otras solicitudes -->
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
                                <th>Horas Trabajadas</th>
                                <th>Descansos</th>
                                <th>Vacaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Juan Pérez</td>
                                <td>Trabajando</td>
                                <td>6h 30m</td>
                                <td>30m</td>
                                <td>12 días restantes</td>
                            </tr>
                            <!-- Otras filas -->
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
                                <tr>
                                    <td>Juan Pérez</td>
                                </tr>
                                <tr>
                                    <td>María López</td>
                                </tr>
                                <!-- Añade más filas según sea necesario -->
                            </tbody>
                        </table>

                        <!-- No trabajando -->
                        <table class="table table-danger table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-center">No Trabajando</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Carla Ruiz</td>
                                </tr>
                                <!-- Añade más filas según sea necesario -->
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
                                <tr>
                                    <td>Pablo Díaz</td>
                                </tr>
                                <!-- Añade más filas según sea necesario -->
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
                                <tr>
                                    <td>Ana Torres</td>
                                </tr>
                                <!-- Añade más filas según sea necesario -->
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
                        data: [10, 5, 3, 2], // Ejemplo de datos
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
