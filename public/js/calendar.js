let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let vacations = []; // Array de vacaciones

function setVacations(vacationDates) {
    vacations = vacationDates;
}

function generateCalendar() {
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = ""; // Limpiar el calendario anterior

    const headerDiv = document.createElement('div');
    headerDiv.className = "d-flex justify-content-between align-items-center mb-3";

    const prevButton = document.createElement('button');
    prevButton.className = "btn btn-outline-secondary";
    prevButton.innerHTML = '<i class="bi bi-chevron-left"></i>';
    prevButton.onclick = () => changeMonth(-1);

    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    const monthTitle = document.createElement('h5');
    monthTitle.className = "m-0";
    monthTitle.textContent = `${monthNames[currentMonth]} ${currentYear}`;

    const nextButton = document.createElement('button');
    nextButton.className = "btn btn-outline-secondary";
    nextButton.innerHTML = '<i class="bi bi-chevron-right"></i>';
    nextButton.onclick = () => changeMonth(1);

    headerDiv.appendChild(prevButton);
    headerDiv.appendChild(monthTitle);
    headerDiv.appendChild(nextButton);
    calendar.appendChild(headerDiv);

    const daysOfWeek = ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"];
    const table = document.createElement('table');
    table.className = "table table-bordered";

    const headerRow = document.createElement('tr');
    daysOfWeek.forEach(day => {
        const th = document.createElement('th');
        th.textContent = day;
        headerRow.appendChild(th);
    });
    table.appendChild(headerRow);

    const firstDay = new Date(currentYear, currentMonth, 7).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    let date = 1;
    for (let i = 0; i < 6; i++) {
        const row = document.createElement('tr');

        for (let j = 0; j < 7; j++) {
            const cell = document.createElement('td');

            if (i === 0 && j < firstDay) {
                cell.textContent = "";
            } else if (date > daysInMonth) {
                break;
            } else {
                cell.textContent = date;

                const currentDate = new Date(currentYear, currentMonth, date);
                const formattedDate = currentDate.toISOString().split('T')[0];

                // Marcar las vacaciones en el calendario
                if (Array.isArray(vacations) && vacations.includes(formattedDate)) {
                    cell.classList.add("bg-success", "text-dark"); // Marca de color amarillo para vacaciones
                }

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

document.addEventListener('DOMContentLoaded', generateCalendar);
