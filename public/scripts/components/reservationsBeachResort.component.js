class ReservationsBeachResortComponent {

    constructor() {
        // Referencias a los elementos.
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");
        const svgImage = document.getElementById("svgImage");
        const lastUnit = document.getElementById("lastUnit");
        const ticket = document.getElementById("ticket");
        const continueBtn = document.getElementById("continueBtn");
        const datosReservation = document.getElementById("reservationForm");
        const objeto = document.getElementsByTagName('object');
        let selectedUnits = [];

        // Si se especificaron ambas fechas, disparamos la consulta.
        function actualizar() {
        if (startDate.value) {
            if (!endDate.value) {
                endDate.value = startDate.value;
            }
            // 1. soltar selecciones
            selectedUnits = [];
            // 2. mostrar ruedita cargando y ocultar svg
            svgImage.style.display = "none";
            const spinner = PAW.crearElementoCargando();
            svgImage.parentNode.appendChild(spinner);
            // 3. disparar petición
            // Construir la URL con los parámetros
            fetch(`/searchReservations?id=${beachResortId}&start_date=${startDate.value}&end_date=${endDate.value}`)
            .then(response => response.json())
            .then(data => {
                /*
                 * "data" es un array de objetos con las unidades del balneario.
                 * Dentro de cada objeto, la propiedad "free" indica si la unidad
                 * está disponible o no.
                 */
                data.forEach(unit => {
                        const svgUnit = svgImage.contentDocument.getElementById(unit.number);
                        if (unit.free) {
                            svgUnit.style.fill = "#8ce189";
                            svgUnit.style.cursor = "pointer";
                            if (!svgUnit.onclick) {
                                svgUnit.onclick = function(event) {
                                    /*
                                     * Vamos por acá. Está el problema de q se carga el listener
                                     * cada vez q cambiamos las fechas. Debe cargarse una sola vez.
                                     */
                                    const idUnit = event.target.id;
                                    const idInArray = selectedUnits.indexOf(idUnit);
                                    if (idInArray >= 0) {
                                        // Lo está soltando...
                                        selectedUnits.splice(idInArray, 1);
                                        event.target.style.fill = "#8ce189";
                                        ticket.innerHTML = Number(ticket.innerHTML) - Number(unit.price);
                                    } else {
                                        // Lo está marcando...
                                        selectedUnits.push(idUnit);
                                        event.target.style.fill = "#0cc0de";
                                        ticket.innerHTML = Number(ticket.innerHTML) + Number(unit.price);
                                    }
                                    /**
                                     * @TODO Hacer que el back devuelva "Carpa" o "Sombrilla"
                                     * en vez de "1" y "2" respectivamente.
                                     */
                                    lastUnit.innerHTML = `${unit.shade} ${unit.number} ${unit.price}`;
                                };
                            }
                        } else {
                            svgUnit.style.fill = "#d0d0d5";
                            svgUnit.style.cursor = "auto";
                            svgUnit.onclick = null;
                        }
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
            // 5. mostrar svg y ocultar ruedita
            spinner.remove();
            svgImage.style.display = "block";

            }
        }        

        continueBtn.addEventListener('click', function(event) {
            startDate.disabled = true;
            endDate.disabled = true;
            continueBtn.style.display = 'none';
        
            fetch(`/searchReservations?id=${beachResortId}&start_date=${startDate.value}&end_date=${endDate.value}`)
                .then(response => response.json())
                .then(data => {
                    /*
                     * "data" es un array de objetos con las unidades del balneario.
                     * Dentro de cada objeto, la propiedad "free" indica si la unidad está disponible o no.
                     */
                    data.forEach(unit => {
                        const svgUnit = svgImage.contentDocument.getElementById(unit.number);
                        if (svgUnit.onclick) {
                            svgUnit.onclick = null;
                            svgUnit.style.cursor = "auto";
                        }
                    });
        
                    // Crear el formulario y agregarlo al documento
                    const newSection = document.createElement('fieldset');
                    newSection.innerHTML = `
                        <h2>Reservar Unidad</h2>
                            <label for="firstName">Nombre:</label>
                            <input type="text" id="firstName" name="firstName" required>
                            <label for="lastName">Apellido:</label>
                            <input type="text" id="lastName" name="lastName" required>
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                            <label for="phone">Teléfono:</label>
                            <input type="tel" id="phone" name="phone" required>
                            <label for="promo">Código promocional:</label>
                            <input type="text" id="promo" name="promo">
                    `;
        
                    datosReservation.appendChild(newSection);

                    const button = document.createElement('button')
                    button.textContent = 'Reservar';
                    datosReservation.appendChild(button);
        
                    // Aquí podrías añadir lógica adicional para manejar el envío del formulario
                    const reservationForm = document.querySelector('#reservationForm');
                    reservationForm.addEventListener('submit', function(event) {
                        console.log(reservationForm);
                        event.preventDefault();
                        // Aquí podrías manejar el envío del formulario (por ejemplo, enviar datos al servidor)
                        // Ejemplo básico:
                        const data = new FormData(reservationForm);
                        const formData =  {
                            beachResortId: beachResortId,
                            startDate: startDate.value,
                            endDate: endDate.value,
                            selectedUnits: selectedUnits,
                            firstName: data.get('firstName'),
                            lastName: data.get('lastName'),
                            email: data.get('email'),
                            phone: data.get('phone'),
                            promo: data.get('promo')

                        };
                        // Aquí podrías enviar los datos al servidor usando fetch u otro método
                        console.log('Formulario enviado:',formData);

                        fetch("/beachResort", {
                            method: "POST",
                            body: JSON.stringify(formData)
                          });
                    });
        
                })
                .catch(error => {
                    console.error('Error al obtener datos:', error);
                    // Aquí podrías manejar el error de la solicitud fetch
                });
        });
        

    startDate.addEventListener('change', actualizar);
    endDate.addEventListener('change', actualizar);

//
//        // Agrega un evento de escucha para el cambio en la fecha de entrada
//        startDate.addEventListener('change', (event) => {
//            // Obtener la fecha de entrada seleccionada
//            const dateIn = event.target.value;
//
//            // Configurar la fecha mínima para el campo de salida
//            endDate.min = dateIn;
//
//            // Resetear el valor de fecha de salida si es menor a la nueva fecha mínima
//            if (endDate.value < dateIn) {
//                endDate.value = dateIn;
//            }
//
//            this.updateUnits();
//        });
//
//        // Agrega un evento de escucha para el cambio en la fecha de salida
//        endDate.addEventListener('change', (event) => {
//            // Obtener la fecha de salida seleccionada
//            const dateOut = event.target.value;
//
//            this.updateUnits();
//        });
//
//        // Agrega un evento de escucha para el clic en el botón
//
//            // Obtener los valores de los campos
//            const dateIn = dateInInput.value;
//            const fechaSalida = fechaSalidaInput.value;
//
//            // Validar que fechaSalida no sea menor que dateIn
//            if (dateIn > fechaSalida) {
//                alert('La fecha de salida debe ser posterior a la fecha de entrada.');
//                return; // Detener la ejecución si la validación falla
//            }
//

//        });
    }


}
