class CarouselComponent {
    constructor(containerSelector, images) {
      this.carousel = containerSelector.tagName ? containerSelector : document.querySelector(containerSelector);
      this.images = images;
      this.currentIndex = 0;
  
      if (this.carousel) {
        this.createCarousel();
        this.loadImages();
      }
    }
  
    createCarousel() {      
      this.imgContainer = document.createElement("section");
      this.imgContainer.classList.add("carousel-images");
  
      this.carousel.appendChild(this.imgContainer);

      this.progressContainer = document.createElement("div");
      this.progressContainer.classList.add("carousel-progress");
      this.progressBar = document.createElement("div");
      this.progressBar.classList.add("progress-bar");
      this.progressContainer.appendChild(this.progressBar);
      this.carousel.appendChild(this.progressContainer);

      // Crear contenedor de miniaturas
      this.thumbnailContainer = document.createElement("div");
      this.thumbnailContainer.classList.add("carousel-thumbnails");

      this.carousel.appendChild(this.thumbnailContainer);
          
      // Crear botones de navegación
      this.prevButton = document.createElement("button");
      this.prevButton.innerHTML = "&lt;";
      this.prevButton.classList.add("carousel-prev-button");
      this.prevButton.type = "button";
  
      this.nextButton = document.createElement("button");
      this.nextButton.innerHTML = "&gt;";
      this.nextButton.classList.add("carousel-next-button");
      this.nextButton.type = "button";
  
      this.carousel.appendChild(this.prevButton);
      this.carousel.appendChild(this.nextButton);
    }
  
    loadImages(){  
        const totalImages = this.images.length;
        let loadedImages = 0;
      
        const onImageLoad = () => {
          loadedImages++;
          const progressPercentage = Math.floor((loadedImages / totalImages) * 100);
          this.updateProgressBar(progressPercentage);
      
          if (loadedImages === totalImages) {
            // Todas las imágenes se han cargado completamente
            this.startCarousel();
          }
        };
      
        this.images.forEach((imageUrl, index) => {
          const image = new Image();
          image.src = imageUrl;
          image.classList.add("carousel-image");
      
          if (image.complete) {
            // La imagen ya está cargada
            onImageLoad();
          } else {
            // Escucha el evento de carga de la imagen
            image.addEventListener("load", onImageLoad);
          }
      
          this.imgContainer.appendChild(image);
      
          // Agregar miniaturas
          const thumbnail = document.createElement("div");
          thumbnail.classList.add("thumbnail");
          thumbnail.style.backgroundImage = `url(${imageUrl})`;
          thumbnail.setAttribute("data-index", index);
          this.thumbnailContainer.appendChild(thumbnail);
        });
    }
  
    startCarousel() {
        const images = this.imgContainer.querySelectorAll(".carousel-image");
        const totalImages = images.length;
        
        const nextSlide = () => {
            this.currentIndex = (this.currentIndex + 1) % totalImages;
            this.showSlide(this.currentIndex);
            setTimeout(nextSlide, 6000);
          };
        
        setTimeout(nextSlide, 6000);

        this.prevButton.addEventListener("click", () => this.showPrevSlide());
        this.nextButton.addEventListener("click", () => this.showNextSlide());
        
        document.addEventListener("keydown", (event) => {
          if (event.key === "ArrowLeft") {
            this.showPrevSlide();
          } else if (event.key === "ArrowRight") {
            this.showNextSlide();
          }
        });
      
        this.thumbnailContainer.addEventListener("click", (event) => {
          if (event.target.classList.contains("thumbnail")) {
            const index = parseInt(event.target.dataset.index);
            this.showSlide(index);
          }
        });
      
        let touchstartX = 0;
        this.imgContainer.addEventListener("touchstart", (event) => {
          touchstartX = event.touches[0].clientX;
        });
      
        this.imgContainer.addEventListener("touchend", (event) => {
          const touchendX = event.changedTouches[0].clientX;
          const threshold = 100; // Píxeles mínimos para reconocer el deslizamiento
      
          if (touchstartX - touchendX > threshold) {
            this.showNextSlide();
          } else if (touchendX - touchstartX > threshold) {
            this.showPrevSlide();
          }
        });
      
        this.showSlide(this.currentIndex);
      }
  
    showSlide(index) {
      const images = this.imgContainer.querySelectorAll(".carousel-image");
  
      images.forEach((image, i) => {
        if (i === index) {
          image.style.display = "block";
        } else {
          image.style.display = "none";
        }
      });

      this.setActiveThumbnail(index);

    }
  
    showNextSlide() {
      const totalImages = this.imgContainer.querySelectorAll(".carousel-image").length;
      this.currentIndex = (this.currentIndex + 1) % totalImages;
      this.showSlide(this.currentIndex);
    }
  
    showPrevSlide() {
      const totalImages = this.imgContainer.querySelectorAll(".carousel-image").length;
      this.currentIndex = (this.currentIndex - 1 + totalImages) % totalImages;
      this.showSlide(this.currentIndex);
    }

    updateProgressBar(percentage) {
      this.progressBar.style.width = `${percentage}%`;
    
      if (percentage === 100) {
        this.progressBar.classList.remove("progress-bar-animated");
        this.progressContainer.style.display = "none";
      }
    }

    setActiveThumbnail(index) {
        const thumbnails = this.thumbnailContainer.querySelectorAll(".thumbnail");
        
        thumbnails.forEach((thumbnail, i) => {
          if (i === index) {
            thumbnail.classList.add("active-thumbnail");
          } else {
            thumbnail.classList.remove("active-thumbnail");
          }
        });
    }

  }

  