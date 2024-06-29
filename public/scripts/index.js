document.addEventListener('DOMContentLoaded', () => {
	Utils.addScript({
		id: 'carousel-component-script',
		src: './scripts/components/carousel.component.js'
	}, this.onLoadScript);
})

function onLoadScript() {
	const carouselId = 'carousel';
	const startCarouselAt = 1;

	const carouselComponent = new CarouselComponent(carouselId, startCarouselAt);
	carouselComponent.render();
}