class ReservationsBeachResortComponent {

    constructor() {
        const botonBuscar = document.querySelector('.bBuscar');
        const fechaEntradaInput = document.getElementById('fecha_entrada');
        const fechaSalidaInput = document.getElementById('fecha_salida');

        // Agrega un evento de escucha para el cambio en la fecha de entrada
        fechaEntradaInput.addEventListener('change', function(event) {
            // Obtener la fecha de entrada seleccionada
            const fechaEntrada = fechaEntradaInput.value;

            // Configurar la fecha mínima para el campo de salida
            fechaSalidaInput.min = fechaEntrada;

            // Resetear el valor de fecha de salida si es menor a la nueva fecha mínima
            if (fechaSalidaInput.value < fechaEntrada) {
                fechaSalidaInput.value = fechaEntrada;
            }
        });

        // Agrega un evento de escucha para el clic en el botón
        botonBuscar.addEventListener('click', function(event) {

            // Obtener los valores de los campos
            const fechaEntrada = fechaEntradaInput.value;
            const fechaSalida = fechaSalidaInput.value;

            // Validar que fechaSalida no sea menor que fechaEntrada
            if (fechaEntrada > fechaSalida) {
                alert('La fecha de salida debe ser posterior a la fecha de entrada.');
                return; // Detener la ejecución si la validación falla
            }

            // Construir la URL con los parámetros
            const url = `/searchReservations?id=${beachResortId}&start_date=${fechaEntrada}&end_date=${fechaSalida}`;

            fetch(url)
            .then(response => response.json())
            .then(data => {
                const reserves = data; // Los datos vienen directamente como objeto
                console.log(reserves);

                const reservesList = document.querySelector('#reservesList');
                reservesList.innerHTML = '';

                const svgObject = document.getElementById('svgImage');
                const svgDocument = svgObject.contentDocument;

                for (const key in reserves) {
                    if (reserves.hasOwnProperty(key)) {
                        const reserve = reserves[key];
                        const listItem = document.createElement('option');
                        const unit = svgDocument.getElementById(reserve.id);
                        if (reserve.free) {
                            unit.style.fill = "#008000";
                        } else {
                            unit.style.fill = "#800000";
                        }
                        listItem.value = reserve.id;
                        listItem.textContent = reserve.id;
                        reservesList.appendChild(listItem);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
        });
    }

}
