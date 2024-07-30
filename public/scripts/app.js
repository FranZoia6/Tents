class appPAW {
	constructor() {
        
        document.addEventListener("DOMContentLoaded", () => {

            //Inicializar la funcionalidad Menu
		    PAW.cargarScript("Hamburguesa", "scripts/components/hamburguesa.component.js", () => {	
                new Hamburguesa();
            });

            let contenedor = document.querySelector(".carousel");

            if (contenedor) {
                PAW.cargarScript("Carousell", "scripts/components/carousel.component.js", () => {
                    const images = [
                        "/imagenes/carpas1.jpeg",
                        "/imagenes/carpas2.jpeg",
                        "/imagenes/carpas3.jpeg"
                    ];
                
                    new CarouselComponent(".carousel", images);
                });
            }

            contenedor = document.querySelector(".cities-list");

            if (contenedor) {
                PAW.cargarScript("Ciudades", "scripts/components/ciudades.component.js", () => {
                    new Ciudades();
                });
            }

            contenedor = document.querySelector(".bReserva");

            if (contenedor) {
                PAW.cargarScript("ReservationHomeComponent", "scripts/components/reservationHome.component.js", () => {
                    new ReservationHomeComponent();
                });
            }

            contenedor = document.querySelector(".reserveUnit");

            if (contenedor) {
                PAW.cargarScript("ReservationsBeachResortComponent", "scripts/components/reservationsBeachResort.component.js", () => {
                    new ReservationsBeachResortComponent();
                });
            }

            contenedor = document.querySelector(".drop-zone");

            if (contenedor) {
                PAW.cargarScript("DragAndDrop", "scripts/components/draganddrop.component.js", () => {
                    new DragAndDrop('dropZoneProfile', 'imagen_perfil', 'previewProfile', 'filenameProfile');
                    new DragAndDrop('dropZoneSVG', 'imagen_svg', 'previewSVG', 'filenameSVG');
                });
            }

        });
    }
}
let app = new appPAW();