class Ciudades {
    constructor() {
        this.ciudadInput = document.getElementById('ciudad');
        this.ciudadList = document.getElementById('cities-list');
        console.log(ciudades);
        // if (ciudades || !Array.isArray(ciudades)) {
        //     console.error('Las ciudades no se han proporcionado o no son un array');
        //     return;
        // }

        // Inicializar el evento de entrada en el campo de texto
        this.ciudadInput.addEventListener('input', () => this.filterCities());

        // Asignar la función de manejar la selección a cada opción de ciudad
        this.ciudadList.addEventListener('click', (event) => {
            if (event.target.classList.contains('ciudad-option')) {
                const cityId = event.target.dataset.id;
                this.handleCitySelect(cityId);
            }
        });

        // Cierra la lista si se hace clic fuera de ella
        document.addEventListener('click', (event) => {
            if (!this.ciudadContainer.contains(event.target)) {
                this.ciudadList.style.display = 'none';
            }
        });

        // Asegúrate de inicializar el contenedor de ciudad
        this.ciudadContainer = document.querySelector('.ciudad-container');
    }

    // Función para filtrar ciudades según la búsqueda
    filterCities() {
        const query = this.ciudadInput.value.toLowerCase();
        this.ciudadList.innerHTML = '';  // Limpiar la lista antes de añadir nuevas opciones
        
        if (query.length > 0) {
            // Filtrar ciudades según la búsqueda
            const filteredCities = ciudades.filter(city => city.fields.name.toLowerCase().includes(query));
            
            filteredCities.forEach(city => {
                const optionItem = document.createElement('li');
                optionItem.textContent = city.fields.name;
                optionItem.dataset.id = city.fields.id;
                optionItem.classList.add('ciudad-option');
                this.ciudadList.appendChild(optionItem);
            });

            // Asegúrate de mostrar la lista si hay resultados
            this.ciudadList.style.display = filteredCities.length > 0 ? 'block' : 'none';
        } else {
            this.ciudadList.style.display = 'none';
        }
    }

    // Función para manejar la selección de una ciudad
    handleCitySelect(cityId) {
        console.log(cityId);
        const selectedCity = ciudades.find(city => city.fields.id == cityId);
        console.log(selectedCity);
        if (selectedCity) {
            this.ciudadInput.value = selectedCity.fields.name;
            this.ciudadList.innerHTML = '';  // Limpiar la lista después de seleccionar una opción
            //this.updateBalnearios(cityId);  // Llamar a la función para actualizar los balnearios
            this.ciudadList.style.display = 'none'; // Ocultar la lista de ciudades después de seleccionar una opción
        }
    }

}

