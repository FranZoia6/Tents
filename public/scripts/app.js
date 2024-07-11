class appPAW {
	constructor() {
        
        document.addEventListener("DOMContentLoaded", () => {

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

            contenedor = document.querySelector(".reserva");

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

            contenedor = document.querySelector(".bContinuar");

            if (contenedor) {
                PAW.cargarScript("ReservationPersonalDataComponent", "scripts/components/reservationPersonalData.component.js", () => {
                    new ReservationPersonalDataComponent();
                });
            }

        });
    }
}
let app = new appPAW();