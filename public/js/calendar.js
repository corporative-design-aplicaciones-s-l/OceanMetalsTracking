let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function generateCalendar() {
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = ""; // Limpiar el calendario anterior

    // Crear el título del mes con flechas de navegación
    const headerDiv = document.createElement('div');
    headerDiv.className = "d-flex justify-content-between align-items-center mb-3";

    // Flecha izquierda para el mes anterior
    const prevButton = document.createElement('button');
    prevButton.className = "btn btn-outline-secondary";
    prevButton.innerHTML = '<i class="bi bi-chevron-left"></i>';
    prevButton.onclick = () => changeMonth(-1);

    // Nombre del mes y año
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    const monthTitle = document.createElement('h5');
    monthTitle.className = "m-0";
    monthTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    // Flecha derecha para el mes siguiente
    const nextButton = document.createElement('button');
    nextButton.className = "btn btn-outline-secondary";
    nextButton.innerHTML = '<i class="bi bi-chevron-right"></i>';
    nextButton.onclick = () => changeMonth(1);

    // Agregar los elementos al encabezado
    headerDiv.appendChild(prevButton);
    headerDiv.appendChild(monthTitle);
    headerDiv.appendChild(nextButton);
    calendar.appendChild(headerDiv);

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
                const today = new Date();
                if (date === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear()) {
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

function changeMonth(direction) {
    currentMonth += direction;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    generateCalendar();
}

// Llamar a la función para generar el calendario cuando el contenido esté listo
document.addEventListener('DOMContentLoaded', generateCalendar);
