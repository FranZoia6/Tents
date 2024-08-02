class ReservationsBeachResortComponent {

    constructor() {
        // Referencias a los elementos.
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");
        const svgImage = document.getElementById("svgImage");
        const precioTotal = document.getElementById("precio_total");
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
                            console.log(unit);
                            if (!svgUnit.onclick) {
                                svgUnit.onclick = function(event) {
                                    const idUnit = unit.id;
                                    console.log(unit);
                                    const idInArray = selectedUnits.indexOf(idUnit);
                                    precioTotal.style.display = "inline";
                                    let currentTotal = parseFloat(ticket.innerHTML) || 0; // Ensure the current total is a number
                                    if (idInArray >= 0) {
                                        // Lo está soltando...
                                        selectedUnits.splice(idInArray, 1);
                                        event.target.style.fill = "#8ce189";
                                    //    ticket.innerHTML = Number(ticket.innerHTML) - Number(unit.price);
                                        currentTotal -= parseFloat(unit.price);
                                        document.getElementById(`number${unit.number}`).remove();
                                    } else {
                                        // Lo está marcando...
                                        selectedUnits.push(idUnit);
                                        event.target.style.fill = "#0cc0de";
                                        currentTotal += parseFloat(unit.price);
                                      //  ticket.innerHTML = Number(ticket.innerHTML) + Number(unit.price);
                                        var priceUnit = showUnitInfo(unit);
                                        lastUnit.appendChild(priceUnit);
                                    }
                                    ticket.innerHTML = currentTotal.toFixed(2); // Update the total with two decimal places
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
        
        function showUnitInfo(unit) {
            const shadeName = getShadeName(unit.shade);
            const formattedPrice = formatPrice(unit.price);
            const priceUnit = document.createElement('div');

            priceUnit.innerHTML = 
                `<div id=number${unit.number} style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9;">
                    <h3>Unidad ${unit.number}</h3>
                    <p><strong>Tipo:</strong> ${shadeName}</p>
                    <p><strong>Precio:</strong> ${formattedPrice}</p>
                </div>`
            ;
            lastUnit.style.display = "block";
            return priceUnit
        }

        function getShadeName(shadeId) {
            const shadeMap = {
                1: 'Carpa',
                2: 'Sombrilla'
                // Agrega más mapeos según sea necesario
            };
            return shadeMap[shadeId] || 'Desconocido'; // Valor por defecto si el ID no está en el mapa
        }
    
        function formatPrice(price) {
            return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'ARS' }).format(price);
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
                        `;
//                          <label for="promo">Código promocional:</label>
//                          <input type="text" id="promo" name="promo">
        
                    datosReservation.appendChild(newSection);

                    const button = document.createElement('input')
                    button.type = "submit";
                    button.value = 'Reservar';
                    button.classname = 'bReservar';
                    datosReservation.appendChild(button);
        
                    // Aquí podrías añadir lógica adicional para manejar el envío del formulario
                    const reservationForm = document.querySelector('#reservationForm');
                    reservationForm.addEventListener('submit', function(event) {
                        console.log(reservationForm);
                        // Aquí podrías manejar el envío del formulario (por ejemplo, enviar datos al servidor)
                        // Ejemplo básico:
                        startDate.disabled = false;
                        endDate.disabled = false;   
                        document.getElementById('selectedUnits').value = selectedUnits;   
                        document.getElementById('reservationAmount').value = Number (ticket.innerHTML);                     

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
