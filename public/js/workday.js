document.addEventListener('DOMContentLoaded', function () {
    checkWorkStatus();
    updateRemainingBreak();
});

const maxBreakMinutes = 180;
let totalBreakMinutes = 0;

function checkWorkStatus() {
    fetch('/workday/status')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'started') {
                document.getElementById('startButton').classList.add('d-none');
                document.getElementById('endButton').classList.remove('d-none');
                document.getElementById('workTimeInfo').classList.remove('d-none');
                document.getElementById('startTime').innerText = data.start_time;

                const startTime = new Date(`1970-01-01T${data.start_time}Z`);
                endTime = new Date(startTime.getTime() + 8 * 60 * 60 * 1000);
                document.getElementById('endTime').innerText = formatTime(endTime);
            } else {
                document.getElementById('startButton').classList.remove('d-none');
                document.getElementById('endButton').classList.add('d-none');
                document.getElementById('workTimeInfo').classList.add('d-none');
            }
        });
}

function startWork() {
    fetch('/workday/start', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                toggleButtons();
                document.getElementById('startTime').innerText = data.workday.start_time;
                const startTime = new Date(`1970-01-01T${data.workday.start_time}Z`);
                endTime = new Date(startTime.getTime() + 8 * 60 * 60 * 1000);
                document.getElementById('endTime').innerText = formatTime(endTime);
                showAlert('Jornada laboral iniciada.', 'success');
            } else {
                alert(data.message);
            }
        });
}

function endWork() {
    fetch('/workday/end', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                toggleButtons();
                showAlert('Jornada laboral terminada.', 'success');
            } else {
                alert(data.message);
            }
        });
}

function applyBreak() {
    const breakSlider = document.getElementById('breakSlider');
    const breakMinutes = parseInt(breakSlider.value);

    if (totalBreakMinutes + breakMinutes > maxBreakMinutes) {
        const remainingMinutes = maxBreakMinutes - totalBreakMinutes;
        showAlert(`No puedes exceder el máximo de 180 minutos de descanso al día. Te quedan ${remainingMinutes} minutos.`, 'warning');
        return;
    }

    totalBreakMinutes += breakMinutes;

    fetch('/workday/break', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ break_minutes: breakMinutes })
    }).then(response => response.json())
        .then(data => {
            endTime = new Date(endTime.getTime() + breakMinutes * 60 * 1000);
            document.getElementById('endTime').innerText = formatTime(endTime);
            updateRemainingBreak();
            showAlert('Descanso aplicado con éxito.', 'success');
        });
}

function updateBreakTime() {
    const breakSlider = document.getElementById('breakSlider');
    const breakTimeDisplay = document.getElementById('breakTimeDisplay');
    breakTimeDisplay.innerText = breakSlider.value;
}

function updateRemainingBreak() {
    const remainingMinutes = maxBreakMinutes - totalBreakMinutes;
    document.getElementById('remainingMinutes').innerText = remainingMinutes;
}

function toggleButtons() {
    document.getElementById('startButton').classList.toggle('d-none');
    document.getElementById('endButton').classList.toggle('d-none');
    document.getElementById('workTimeInfo').classList.toggle('d-none');
}

function formatTime(date) {
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
}

// Función para mostrar alertas de Bootstrap
function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');

    // Eliminar cualquier alerta existente antes de mostrar una nueva
    alertContainer.innerHTML = '';

    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show floating-alert`;
    alert.role = 'alert';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    alertContainer.appendChild(alert);

    // Remover el alert automáticamente después de 5 segundos con un fade-out
    setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('fade-out');
        setTimeout(() => alert.remove(), 500);
    }, 5000);
}
