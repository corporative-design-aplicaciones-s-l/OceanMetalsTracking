@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Registro de Vacaciones</h4>

        <div class="row">
            <!-- Columna Izquierda: Calendario de vacaciones -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-calendar3"></i> Calendario de Vacaciones
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Resumen de vacaciones -->
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> Resumen de Vacaciones
                    </div>
                    <div class="card-body">
                        <p><strong>Días restantes:</strong> {{ $remainingDays }}</p>
                        <p><strong>Días disfrutados:</strong> {{ $usedDays }}</p>
                        <p><strong>Días solicitados:</strong> {{ $requestedDays }}</p>
                        <p><strong>Días confirmados:</strong> {{ $confirmedDays }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Solicitud de Vacaciones -->
        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-calendar-plus"></i> Solicitar Vacaciones
            </div>
            <div class="card-body">
                <form action="{{ route('vacations.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date">Fecha de inicio:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date">Fecha de fin:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Solicitar Vacaciones</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Cargar el archivo JavaScript externo -->
    <script src="{{ asset('js/calendar.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const vacationDates = @json($vacationDates); // Pasar datos desde PHP
            setVacations(vacationDates); // Llamar a la función con las fechas generadas
            generateCalendar(); // Renderizar el calendario
        });
    </script>
@endsection
