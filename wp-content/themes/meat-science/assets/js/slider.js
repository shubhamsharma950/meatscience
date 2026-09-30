document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('[data-slider]').forEach(function (slider) {
		var slides = Array.from(slider.querySelectorAll('[data-slide]'));
		var dots = Array.from(slider.querySelectorAll('[data-slider-dot]'));
		var previousButton = slider.querySelector('[data-slider-previous]');
		var nextButton = slider.querySelector('[data-slider-next]');
		var autoplayDelay = Number(slider.dataset.autoplay);
		var currentIndex = 0;
		var autoplayTimer;

		if (slides.length < 2) {
			return;
		}

		function showSlide(index) {
			currentIndex = (index + slides.length) % slides.length;

			slides.forEach(function (slide, slideIndex) {
				var isActive = slideIndex === currentIndex;
				slide.classList.toggle('is-active', isActive);
				slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
			});

			dots.forEach(function (dot, dotIndex) {
				var isActive = dotIndex === currentIndex;
				dot.classList.toggle('is-active', isActive);
				dot.setAttribute('aria-current', isActive ? 'true' : 'false');
			});
		}

		function stopAutoplay() {
			window.clearInterval(autoplayTimer);
		}

		function startAutoplay() {
			stopAutoplay();

			if (autoplayDelay > 0 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
				autoplayTimer = window.setInterval(function () {
					showSlide(currentIndex + 1);
				}, autoplayDelay);
			}
		}

		previousButton.addEventListener('click', function () {
			showSlide(currentIndex - 1);
			startAutoplay();
		});

		nextButton.addEventListener('click', function () {
			showSlide(currentIndex + 1);
			startAutoplay();
		});

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				showSlide(Number(dot.dataset.sliderDot));
				startAutoplay();
			});
		});

		slider.addEventListener('mouseenter', stopAutoplay);
		slider.addEventListener('mouseleave', startAutoplay);
		slider.addEventListener('focusin', stopAutoplay);
		slider.addEventListener('focusout', startAutoplay);
		startAutoplay();
	});

	document.querySelectorAll('[data-cpt-slider-track]').forEach(function (track) {
		var wrapper = track.closest('.meat-science-cpt-slider__wrapper');

		if (!wrapper) {
			return;
		}

		var previousButton = wrapper.querySelector('[data-cpt-slider-previous]');
		var nextButton = wrapper.querySelector('[data-cpt-slider-next]');
		var autoplayDelay = 4000;
		var autoplayTimer;

		function cardStep() {
			var card = track.querySelector('.meat-science-cpt-slider__card');

			if (!card) {
				return 0;
			}

			var style = window.getComputedStyle(track);
			var gap = parseFloat(style.columnGap || style.gap) || 0;

			return card.getBoundingClientRect().width + gap;
		}

		function maxScrollLeft() {
			return track.scrollWidth - track.clientWidth;
		}

		function goToPrevious() {
			if (track.scrollLeft <= 1) {
				track.scrollTo({ left: maxScrollLeft(), behavior: 'smooth' });
			} else {
				track.scrollBy({ left: -cardStep(), behavior: 'smooth' });
			}
		}

		function goToNext() {
			if (track.scrollLeft >= maxScrollLeft() - 1) {
				track.scrollTo({ left: 0, behavior: 'smooth' });
			} else {
				track.scrollBy({ left: cardStep(), behavior: 'smooth' });
			}
		}

		function stopAutoplay() {
			window.clearInterval(autoplayTimer);
		}

		function startAutoplay() {
			stopAutoplay();

			if (maxScrollLeft() > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
				autoplayTimer = window.setInterval(goToNext, autoplayDelay);
			}
		}

		previousButton.addEventListener('click', function () {
			goToPrevious();
			startAutoplay();
		});

		nextButton.addEventListener('click', function () {
			goToNext();
			startAutoplay();
		});

		wrapper.addEventListener('mouseenter', stopAutoplay);
		wrapper.addEventListener('mouseleave', startAutoplay);
		wrapper.addEventListener('focusin', stopAutoplay);
		wrapper.addEventListener('focusout', startAutoplay);
		window.addEventListener('resize', startAutoplay);
		startAutoplay();
	});

	var videoModal = document.querySelector('[data-video-modal]');

	if (videoModal) {
		var videoModalBody = videoModal.querySelector('[data-video-modal-body]');
		var lastFocusedElement = null;

		function closeVideoModal() {
			videoModal.setAttribute('hidden', '');
			videoModalBody.innerHTML = '';
			document.body.classList.remove('has-video-modal-open');

			if (lastFocusedElement) {
				lastFocusedElement.focus();
			}
		}

		function openVideoModal(type, src) {
			if (!src) {
				return;
			}

			lastFocusedElement = document.activeElement;
			videoModalBody.innerHTML = '';

			var media;

			if (type === 'video') {
				media = document.createElement('video');
				media.src = src;
				media.controls = true;
				media.autoplay = true;
				media.playsInline = true;
			} else {
				media = document.createElement('iframe');
				media.src = src;
				media.allow = 'autoplay; fullscreen; picture-in-picture; encrypted-media';
				media.allowFullscreen = true;
			}

			videoModalBody.appendChild(media);
			videoModal.removeAttribute('hidden');
			document.body.classList.add('has-video-modal-open');
			videoModal.querySelector('[data-video-modal-close]').focus();
		}

		document.querySelectorAll('[data-video-trigger]').forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				openVideoModal(trigger.dataset.videoType, trigger.dataset.videoSrc);
			});
		});

		videoModal.querySelectorAll('[data-video-modal-close]').forEach(function (closeControl) {
			closeControl.addEventListener('click', closeVideoModal);
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape' && !videoModal.hasAttribute('hidden')) {
				closeVideoModal();
			}
		});
	}
});
