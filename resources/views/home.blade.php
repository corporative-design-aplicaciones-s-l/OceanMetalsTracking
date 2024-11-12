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
    <script>
        let endTime;
        let totalBreakMinutes = 0;
        const maxBreakMinutes = 180;

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

            const startTime = new Date();
            document.getElementById('startTime').innerText = formatTime(startTime);

            endTime = new Date(startTime.getTime() + 8 * 60 * 60 * 1000);
            document.getElementById('endTime').innerText = formatTime(endTime);

            // Registrar inicio en la base de datos
            fetch('{{ route('workday.start') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                }).then(response => response.json())
                .then(data => console.log(data));
        }

        function endWork() {
            toggleButtons();

            fetch('{{ route('workday.end') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                }).then(response => response.json())
                .then(data => console.log(data));
        }

        function updateBreakTime() {
            const breakSlider = document.getElementById('breakSlider');
            const breakTimeDisplay = document.getElementById('breakTimeDisplay');
            breakTimeDisplay.innerText = breakSlider.value;
        }

        function applyBreak() {
            const breakSlider = document.getElementById('breakSlider');
            const breakMinutes = parseInt(breakSlider.value);

            if (totalBreakMinutes + breakMinutes > maxBreakMinutes) {
                alert("No puedes exceder el máximo de 180 minutos de descanso al día.");
                return;
            }

            totalBreakMinutes += breakMinutes;

            fetch('{{ route('workday.break') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        break_minutes: breakMinutes
                    })
                }).then(response => response.json())
                .then(data => console.log(data));

            endTime = new Date(endTime.getTime() + breakMinutes * 60 * 1000);
            document.getElementById('endTime').innerText = formatTime(endTime);
            updateRemainingBreak();
        }

        function updateRemainingBreak() {
            const remainingMinutes = maxBreakMinutes - totalBreakMinutes;
            document.getElementById('remainingMinutes').innerText = remainingMinutes;
        }

        function formatTime(date) {
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        updateRemainingBreak();
    </script>
@endsection
