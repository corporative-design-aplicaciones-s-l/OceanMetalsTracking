@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Bienvenido <strong>{{ Auth::user()->name }}</strong> </h4>

        <!-- Contenedor de alertas flotantes -->
        <div id="alertContainer" class="floating-alert-container"></div>

        <div class="row">
            <!-- Columna Izquierda: Jornada Laboral y Descanso -->
            <div class="col-md-6">
                <!-- Sección Jornada Laboral -->
                <div class="card my-4">
                    <div class="card-header">
                        <i class="bi bi-briefcase"></i> Jornada laboral
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <!-- Botones -->
                        <div class="buttons-container">
                            <button id="startButton" class="btn btn-danger mb-2 d-none" onclick="startWork()">
                                <i class="bi bi-hourglass-split"></i> Empezar jornada laboral
                            </button>
                            <button id="endButton" class="btn btn-info mb-2 d-none" onclick="endWork()">
                                <i class="bi bi-hourglass-split"></i> Terminar jornada laboral
                            </button>
                        </div>

                        <!-- Información de Tiempo -->
                        <div class="time-info ms-4">
                            <!-- Tiempo de trabajo restante -->
                            <div id="remainingTimeInfo" class="d-none">
                                <p><strong>Tiempo de trabajo restante:</strong> <span id="remainingTime">8h 0m</span></p>
                            </div>
                            <!-- Hora de inicio y fin estimada -->
                            <div id="workTimeInfo" class="d-none">
                                <p><strong>Hora de inicio:</strong> <span id="startTime">--:--</span></p>
                                <p><strong>Hora de fin estimada:</strong> <span id="endTime">--:--</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Descanso -->
                <div class="card ">
                    <div class="card-header">
                        <i class="bi bi-cup-fill"></i> Descanso (intervalo de descanso)
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <input type="range" id="breakSlider" class="form-range" min="10" max="180"
                                step="10" value="10" oninput="updateBreakTime()">
                            <span id="breakTimeDisplay">10</span> Minutos
                        </div>
                        <button class="btn btn-success mt-3" onclick="applyBreak()">Aplicar descanso</button>
                        <p id="remainingBreak" class="mt-2 text-muted">Tiempo de descanso restante: <span
                                id="remainingMinutes">180</span> minutos</p>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Calendario -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-calendar3"></i> Calendario
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Cargar el archivo JavaScript externo -->
    <script src="{{ asset('js/workday.js') }}"></script>
    <!-- Script Calendario -->
    <script src="{{ asset('js/calendar.js') }}"></script>
@endsection
