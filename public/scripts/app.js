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
        });
    }
}
let app = new appPAW();