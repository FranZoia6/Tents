class ReservationsBeachResortComponent {

    constructor() {
        // Referencias a los elementos.
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");
        const svgImage = document.getElementById("svgImage");
        const lastUnit = document.getElementById("lastUnit");
        const ticket = document.getElementById("ticket");
        const continueBtn = document.getElementById("continueBtn");
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
                        const svgUnit = svgImage.contentDocument.getElementById(unit.id);
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

        // Agrega un evento de escucha para el clic en el botón
        continueBtn.addEventListener('click', function(event) {

            startDate.disabled = true;
            endDate.disabled = true;
            // svgImage.style.fill = 'rgba(0, 0, 0, 0.75)';
            fetch(`/searchReservations?id=${beachResortId}&start_date=${startDate.value}&end_date=${endDate.value}`)
            .then(response => response.json())
            .then(data => {
                /*
                * "data" es un array de objetos con las unidades del balneario.
                * Dentro de cada objeto, la propiedad "free" indica si la unidad
                 * está disponible o no.
                */
               data.forEach(unit => {
                        const svgUnit = svgImage.contentDocument.getElementById(unit.id);
                        if (svgUnit.onclick) {
                            svgUnit.onclick = null 
                            svgUnit.style.cursor = "auto";
                        }
                    });
                })
                
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
