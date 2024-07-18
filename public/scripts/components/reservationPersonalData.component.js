class ReservationPersonalDataComponent {

    constructor() {
        const continueBtn = document.querySelector('.bContinuar');
        const fechaEntradaInput = document.getElementById('startDate');
        const fechaSalidaInput = document.getElementById('endDate');

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
        continueBtn.addEventListener('click', function(event) {

            // Obtener los valores de los campos
            const fechaEntrada = fechaEntradaInput.value;
            const fechaSalida = fechaSalidaInput.value;

            // Validar que fechaSalida no sea menor que fechaEntrada
            if (fechaEntrada > fechaSalida) {
                alert('La fecha de salida debe ser posterior a la fecha de entrada.');
                return; // Detener la ejecución si la validación falla
            }

            // Construir la URL con los parámetros
            const url = `/reservationPersonalData`;

            // Redirigir al usuario
            window.location.href = url;

        });
    }

}
