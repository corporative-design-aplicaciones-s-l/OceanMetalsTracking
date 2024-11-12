@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Control Jornada Laboral</h2>
        <h4><strong>Trabajador:</strong> {{ Auth::user()->name }}</h4>

        <!-- Contenedor de alertas flotantes -->
        <div id="alertContainer" class="floating-alert-container"></div>

        <!-- Sección Jornada Laboral -->
        <div class="card my-4">
            <div class="card-header">
                <i class="bi bi-briefcase"></i> Jornada laboral
            </div>
            <div class="card-body text-center">
                <button id="startButton" class="btn btn-danger mb-2 d-none" onclick="startWork()">
                    <i class="bi bi-hourglass-split"></i> Empezar jornada laboral
                </button>
                <button id="endButton" class="btn btn-info mb-2 d-none" onclick="endWork()">
                    <i class="bi bi-hourglass-split"></i> Terminar jornada laboral
                </button>

                <!-- Hora de inicio y fin estimado -->
                <div id="workTimeInfo" class="mt-3 d-none">
                    <p><strong>Hora de inicio:</strong> <span id="startTime">--:--</span></p>
                    <p><strong>Hora de fin estimada:</strong> <span id="endTime">--:--</span></p>
                </div>
            </div>
        </div>

        <!-- Sección Descanso -->
        <div class="card my-4">
            <div class="card-header">
                <i class="bi bi-cup-fill"></i> Descanso (intervalo de descanso)
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Minutos: 10</span>
                    <input type="range" id="breakSlider" class="form-range" min="10" max="180" step="10"
                        value="10" oninput="updateBreakTime()">
                    <span id="breakTimeDisplay">10</span> Minutos
                </div>
                <button class="btn btn-success mt-3" onclick="applyBreak()">Aplicar descanso</button>
                <p id="remainingBreak" class="mt-2 text-muted">Tiempo de descanso restante: <span
                        id="remainingMinutes">180</span> minutos</p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Cargar el archivo JavaScript externo -->
    <script src="{{ asset('js/workday.js') }}"></script>
@endsection
