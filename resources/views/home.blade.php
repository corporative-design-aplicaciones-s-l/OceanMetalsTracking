@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Control Jornada Laboral</h2>
        <h4><strong>Trabajador:</strong> {{ Auth::user()->name }}</h4>

        <!-- Sección Jornada Laboral -->
        <div class="card my-4">
            <div class="card-header">
                <i class="bi bi-briefcase"></i> Jornada laboral
            </div>
            <div class="card-body text-center">
                <button id="startButton" class="btn btn-danger mb-2" onclick="startWork()">
                    <i class="bi bi-hourglass-split"></i> Empezar jornada laboral
                </button>
                <button id="endButton" class="btn btn-info mb-2 d-none" onclick="toggleButtons()">
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
    <script>
        let endTime;
        let totalBreakMinutes = 0; // Almacena el total de minutos de descanso aplicados
        const maxBreakMinutes = 180; // Máximo de descanso permitido al día

        function toggleButtons() {
            const startButton = document.getElementById('startButton');
            const endButton = document.getElementById('endButton');
            const workTimeInfo = document.getElementById('workTimeInfo');

            startButton.classList.toggle('d-none');
            endButton.classList.toggle('d-none');
            workTimeInfo.classList.toggle('d-none');
        }

        function startWork() {
            toggleButtons();

            // Obtener la hora actual y mostrarla como hora de inicio
            const startTime = new Date();
            document.getElementById('startTime').innerText = formatTime(startTime);

            // Calcular la hora de fin estimada (asumimos una jornada de 8 horas)
            endTime = new Date(startTime.getTime() + 8 * 60 * 60 * 1000); // 8 horas en milisegundos
            document.getElementById('endTime').innerText = formatTime(endTime);
        }

        function formatTime(date) {
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        function updateBreakTime() {
            // Actualiza el valor mostrado del intervalo de descanso en minutos
            const breakSlider = document.getElementById('breakSlider');
            const breakTimeDisplay = document.getElementById('breakTimeDisplay');
            breakTimeDisplay.innerText = breakSlider.value;
        }

        function applyBreak() {
            const breakSlider = document.getElementById('breakSlider');
            const breakMinutes = parseInt(breakSlider.value);

            // Verificar si el total acumulado + el descanso actual no supera el máximo permitido
            if (totalBreakMinutes + breakMinutes > maxBreakMinutes) {
                alert("No puedes exceder el máximo de 180 minutos de descanso al día.");
                return;
            }

            // Añadir el descanso al total acumulado
            totalBreakMinutes += breakMinutes;

            // Actualizar la hora de fin estimada
            endTime = new Date(endTime.getTime() + breakMinutes * 60 * 1000);
            document.getElementById('endTime').innerText = formatTime(endTime);

            // Actualizar el tiempo de descanso restante
            updateRemainingBreak();
        }

        function updateRemainingBreak() {
            // Calcula y muestra el tiempo de descanso restante
            const remainingMinutes = maxBreakMinutes - totalBreakMinutes;
            document.getElementById('remainingMinutes').innerText = remainingMinutes;
        }

        // Inicializar el tiempo de descanso restante al cargar la página
        updateRemainingBreak();
    </script>
@endsection
