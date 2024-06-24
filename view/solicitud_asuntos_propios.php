<?php

// Asigna la variable de sesión si no está definida
if (!isset($_SESSION['correo'])) {
    $_SESSION['correo'] = $correo;
}

// Verifica si la variable de sesión está definida
if (!isset($_SESSION['correo'])) {
    header("Location: index.php");
    exit();
}

$dias = $dataToView['dias'];
$dni = $_SESSION['dni'];
$trace = new Trace();
$festivos = $trace->ver_festivos();
$festivos_json = json_encode(array_column($festivos, 'FEST_FECHA'));
$turno = $trace->turno_empleado();

?>

<div class="container mt-5">
    <form id="asuntos-form" action="index.php?action=asuntos_propios" method="post" class="needs-validation" novalidate>
        <div class="header d-flex justify-content-between align-items-center px-5 ms-xl-4 mb-2" style="height: 100px;">
            <img class="logo" src="view/template/imagenes/trace4-sin-fondo.png" alt="LOGOTIPO TRACE">
            <div class="perfil-titulo">
                <h1>Elegir Días de Asuntos Propios</h1>
            </div>
        </div>
        <input type="hidden" id="selected-date" name="selected_date">
        <input type="hidden" id="dias" name="dias" value="<?php echo $dias; ?>">
        <input type="hidden" id="turno" name="turno" value="<?php echo $turno; ?>">
        <div class="calendar">
            <div class="calendar__info">
                <div class="calendar__prev" id="prev-month">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </div>
                <div class="calendar__month" id="month"></div>
                <div class="calendar__year" id="year"></div>
                <div class="calendar__next" id="next-month">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </div>
            </div>
            <div class="calendar__week">
                <div class="calendar__day calendar__item">Lu</div>
                <div class="calendar__day calendar__item">Ma</div>
                <div class="calendar__day calendar__item">Mi</div>
                <div class="calendar__day calendar__item">Ju</div>
                <div class="calendar__day calendar__item">Vi</div>
                <div class="calendar__day calendar__item">Sá</div>
                <div class="calendar__day calendar__item">Do</div>
            </div>
            <div>
                <div class="calendar__dates" id="dates"></div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary me-2 mr-2">Enviar</button>
            <a href="index.php?action=logeado" class="btn btn-primary">Volver a la página principal</a>
        </div>
    </form>
</div>

<script>
    let monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    let currentDate = new Date();
    let currentDay = currentDate.getDate();
    let monthNumber = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let currentMonth = monthNames[monthNumber];


    let dates = document.getElementById('dates');
    let month = document.getElementById('month');
    let year = document.getElementById('year');

    let prevMonthDOM = document.getElementById('prev-month');
    let nextMonthDOM = document.getElementById('next-month');

    month.textContent = monthNames[monthNumber];
    year.textContent = currentYear.toString();

    prevMonthDOM.addEventListener('click', () => lastMonth());
    nextMonthDOM.addEventListener('click', () => nextMonth());

    let festivos = <?= $festivos_json; ?>;

    writeMonth(monthNumber);

    function writeMonth(month) {
        const minDate = getMinDate();
        const maxDate = getMaxDate();

        for (let i = startDay(); i > 0; i--) {
            dates.innerHTML +=
                `<div class="calendar__date calendar__item calendar__last-days">${getTotalDays(monthNumber - 1) - (i - 1)}</div>`;
        }

        for (let i = 1; i <= getTotalDays(month); i++) {
            let date = new Date(currentYear, month, i);
            let dateString = `${currentYear}-${(month + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            let isSelectable = date >= minDate && date <= maxDate;
            let isCurrentDay = (i === currentDay && month === currentDate.getMonth() && currentYear === currentDate
                .getFullYear());
            let isHoliday = festivos.includes(dateString);

            if (isHoliday) {
                dates.innerHTML += `<div class="calendar__date calendar__item calendar__date--holiday">${i}</div>`;
            } else if (isSelectable) {
                dates.innerHTML += `<div class="calendar__date calendar__item selectable">${i}</div>`;
            } else {
                dates.innerHTML += `<div class="calendar__date calendar__item non-selectable">${i}</div>`;
            }
        }

        addDateClickEvent();
    }


    function getTotalDays(month) {
        if (month === -1) month = 11;

        if (month == 0 || month == 2 || month == 4 || month == 6 || month == 7 || month == 9 || month == 11) {
            return 31;
        } else if (month == 3 || month == 5 || month == 8 || month == 10) {
            return 30;
        } else {
            return isLeap() ? 29 : 28;
        }
    }

    /* año bisiesto */
    function isLeap() {
        return ((currentYear % 100 !== 0) && (currentYear % 4 === 0) || (currentYear % 400 === 0));
    }

    function startDay() {
        let start = new Date(currentYear, monthNumber, 1);
        return ((start.getDay() - 1) === -1) ? 6 : start.getDay() - 1;
    }

    function lastMonth() {
        if (monthNumber !== 0) {
            monthNumber--;
        } else {
            monthNumber = 11;
            currentYear--;
        }

        setNewDate();
    }

    function nextMonth() {
        if (monthNumber !== 11) {
            monthNumber++;
        } else {
            monthNumber = 0;
            currentYear++;
        }

        setNewDate();
    }

    function setNewDate() {
        currentDate.setFullYear(currentYear, monthNumber, currentDay);
        month.textContent = monthNames[monthNumber];
        year.textContent = currentYear.toString();

        dates.textContent = "";
        writeMonth(monthNumber);
    }

    function getMinDate() {
        let minDate = new Date();
        minDate.setDate(minDate.getDate() + 7);
        return minDate;
    }

    function getMaxDate() {
        let maxDate = new Date();
        maxDate.setMonth(maxDate.getMonth() + 6);
        return maxDate;
    }

    function addDateClickEvent() {
        const dateElements = document.querySelectorAll('.selectable');
        dateElements.forEach(dateElement => {
            dateElement.addEventListener('click', function() {
                // Desmarca otros días seleccionados
                dateElements.forEach(de => de.classList.remove('selected'));
                // Marca el día seleccionado
                this.classList.add('selected');

                // Obtiene la hora actual
                let now = new Date();
                let hours = now.getHours().toString().padStart(2, '0');
                let minutes = now.getMinutes().toString().padStart(2, '0');
                let seconds = now.getSeconds().toString().padStart(2, '0');

                // Actualiza el campo oculto con la fecha y hora seleccionada
                document.getElementById('selected-date').value =
                    `${this.textContent}-${monthNumber + 1}-${currentYear} ${hours}:${minutes}:${seconds}`;
            });
        });
    }

    document.getElementById('asuntos-form').addEventListener('submit', function(event) {
        let selectedDate = document.getElementById('selected-date').value;
        if (selectedDate) {
            let [day, month, yearWithTime] = selectedDate.split('-');
            let year = yearWithTime.split(' ')[0];
            let formattedDate = `${day} de ${monthNames[month - 1]} de ${year}`;
            let confirmMessage =
                `¿Estás seguro de que deseas seleccionar el ${formattedDate} como día de asuntos propios?`;
            if (!window.confirm(confirmMessage)) {
                event.preventDefault(); // Cancela el envío del formulario
            }
        } else {
            alert('Por favor, selecciona una fecha antes de enviar el formulario.');
            event.preventDefault(); // Cancela el envío del formulario si no se ha seleccionado una fecha
        }
    });
</script>