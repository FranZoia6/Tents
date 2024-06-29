class CarouselComponent {
	constructor(carouselId, startSlideIndex) {
		this.carouselElement = document.getElementById(carouselId);
		this.slideIndex = startSlideIndex;

		this.xDown = null;
		this.yDown = null;
	}

	render() {
		Utils.addLink({
			id: 'carousel-component-style-link',
			type: 'text/css',
			rel: 'stylesheet',
			href: './scripts/components/styles/carousel.css',
		},
		this.onLoadScript.bind(this))
	}

	onLoadScript() {
		const slidesContainer = this.carouselElement.children['carousel-slides-container'];

		slidesContainer.addEventListener('click', () => console.log('click'), false);

		slidesContainer.style.textAlign = 'center'
		const { children: slides } = slidesContainer;

		const images = Array.from(slides).map(slide => Array.from(slide.children)).flat().filter(child => child.tagName === 'IMG');

		// Adds the loading effect on the images that have not been loaded yet
		this.addImagesLoadingEffectIfNeeded(images);

		// Add JS custom classes to the carousel slides
		this.addClassesToSlides(slides);

		// Creates the container section with the slide dots inside
		const dotContainerElement = this.createSlideDotsElements(slides);
		this.carouselElement.appendChild(dotContainerElement);

		// Creates the "Previous" Button
		const buttonPrevious = this.createPreviousSlideButton();
		this.carouselElement.appendChild(buttonPrevious);

		// Creates the "Next" Button
		const buttonNext = this.createNextSlideButton();
		this.carouselElement.appendChild(buttonNext);

		// Adds the left/right arrow events
		document.onkeydown = this.onKeyDownEvent.bind(this);

		document.addEventListener('touchstart', this.handleTouchStart.bind(this), false);
		document.addEventListener('touchmove', this.handleTouchMove.bind(this), false);

		this.showSlides(this.slideIndex);
	}

	getTouches(event) {
		return event.touches;
	}

	handleTouchStart(event) {
		const firstTouch = this.getTouches(event)[0];
		this.xDown = firstTouch.clientX;
		this.yDown = firstTouch.clientY;
	}

	handleTouchMove(event) {
		if (!this.xDown || !this.yDown) return;
	
		const xUp = event.touches[0].clientX;
		const yUp = event.touches[0].clientY;

		const xDiff = this.xDown - xUp;
		const yDiff = this.yDown - yUp;

		if (Math.abs( xDiff ) > Math.abs( yDiff ) ) {
			if (xDiff > 0) {
				/* right swipe */ 
				this.showSlides(this.slideIndex + 1);
			} else {
				/* left swipe */
				this.showSlides(this.slideIndex - 1);
			}
		} else {
			if (yDiff > 0) {
				/* down swipe */ 
			} else { 
				/* up swipe */
			}
		}
		/* reset values */
		this.xDown = null;
		this.yDown = null;
	}

	onKeyDownEvent(event) {
		if (event.code === 'ArrowRight') this.showSlides(this.slideIndex + 1);
		if (event.code === 'ArrowLeft') this.showSlides(this.slideIndex - 1);
	}

	// Next/previous controls
	plusSlides(n) {
		this.showSlides(this.slideIndex += n);
	}

	// Thumbnail image controls
	currentSlide(n) {
		this.showSlides(n);
	}

	showSlides(newIndex) {
		// Gets all the slides
		const slides = this.carouselElement.getElementsByClassName("carousel-slide");
		// gets all the dots
		const dots = this.carouselElement.getElementsByClassName("dot");
		// If the new index goes beyond the last slide, then go to the beggining
		if (newIndex > slides.length) newIndex = 1;
		// If the new index goes before the first slide, then go to the end
		if (newIndex < 1) newIndex = slides.length;

		for (let i = 0; i < slides.length; i++) {
			// Displays "none" on every slide
			slides[i].style.display = "none";
			// Deactivate all the dots
			dots[i].classList.remove('active');
		}

		// Display visible the selected slide
		slides[newIndex - 1].style.display = "inline-block";
		// Display visible the selected dot
		dots[newIndex - 1].classList.add('active');

		this.slideIndex = newIndex;
	}

	createNextSlideButton() {
		const buttonNext = document.createElement('a');
		buttonNext.classList.add('next');
		buttonNext.onclick = () => this.plusSlides(1);
		buttonNext.innerHTML = '&#10095' // > Simbol
		return buttonNext;
	}

	createPreviousSlideButton() {
		const buttonPrevious = document.createElement('a');
		buttonPrevious.classList.add('prev');
		buttonPrevious.onclick = () => this.plusSlides(-1);
		buttonPrevious.innerHTML = '&#10094' // < Simbol
		return buttonPrevious;
	}

	createLoadingEffectElement() {
		const loadingSpan = document.createElement('span');
		loadingSpan.classList.add('loading');
		return loadingSpan;
	}

	addImagesLoadingEffectIfNeeded(images) {
		for (const image of images) {
			// const parent = image.parentNode;
			// const loadingEffect = this.createLoadingEffectElement();
			// parent.appendChild(loadingEffect);
			// continue
			if (image.complete) continue;

			const parent = image.parentNode;
			const loadingEffect = this.createLoadingEffectElement();
			parent.appendChild(loadingEffect);
			image.onload = () => {
				loadingEffect.remove();
			}
		}
	}

	addClassesToSlides(slides) {
		for (const slide of slides) {
			slide.classList.add("carousel-slide-js-inserted");
			slide.classList.add("fade");
		}
	}

	createSlideDotsElements(slides) {
		const dotsContainerElement = document.createElement('section');
		dotsContainerElement.id = 'carousel-dots-container';
		dotsContainerElement.style.textAlign = 'center';
		dotsContainerElement.style.backgroundColor = 'transparent';
		dotsContainerElement.style.margin = '1rem';

		for (let index = 0; index < slides.length; index++) {
			const slide = slides[index];
			// Create a Dot for each slide
			const dotElement = this.createSlideDotElement(index);
			// Append the created dot to the container
			dotsContainerElement.appendChild(dotElement);
		}

		return dotsContainerElement;
	}

	createSlideDotElement(slideIndex) {
		const dotElement = document.createElement('span');
		dotElement.classList.add('dot');
		dotElement.onclick = () => this.currentSlide(slideIndex + 1);
		return dotElement;
	}
}