class Ciudades {

    constructor() {
        this.fetchCities()
            .then(() => {
                this.cargarCiudades();

                // Agregar evento change al selector de ciudades
                const selectCiudades = document.querySelector('.selectCiudades');
                selectCiudades.addEventListener('change', () => {
                    const selectedCityId = selectCiudades.value;
                    this.fetchResorts(selectedCityId);
                    console.log(selectedCityId);

                });

            });

        // Espera 3 segundos y luego oculta la alerta
        setTimeout(function() {
            var alerta = document.querySelector('.alerta');
            if (alerta) {
                alerta.style.display = 'none';
            }
        }, 8000);
    }

    fetchCities() {
        return fetch('/cities')
          .then(response => response.json())
          .then(data => {
            this.cities = data.data;
            console.log(this.cities);
          });
    }
    
    cargarCiudades() {
        const selectCiudades = document.querySelector('.selectCiudades');
        const initialOption = PAW.nuevoElemento('option', "Ingrese una ciudad", {value: 0});
        selectCiudades.appendChild(initialOption);
        this.cities.forEach(city => {
            const option = PAW.nuevoElemento('option',  city.name, {
                value: city.id
            });
            selectCiudades.appendChild(option);
        }); 
    }

    fetchResorts(cityId) {
        // Realizar fetch para obtener balnearios de la ciudad seleccionada
        fetch(`/resorts?cityId=${cityId}`)
          .then(response => response.json())
          .then(data => {
            this.displayResorts(data);
          })
          .catch(error => {
            console.error('Error fetching resorts:', error);
          });
    }

    displayResorts(resorts) {
        // Actualizar la interfaz para mostrar los balnearios
        console.log(resorts);
        const resortsList = document.querySelector('.balnearios');
        resortsList.innerHTML = ''; // Limpiar la lista anterior

        resorts.forEach(resort => {
            const listItem = PAW.nuevoElemento('option', resort.name);
            resortsList.appendChild(listItem);
        });
    }
}
