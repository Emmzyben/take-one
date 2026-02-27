/* ===================================
--------------------------------------
  TheQuest - Gaming Magazine Template
  Version: 1.0
--------------------------------------
======================================*/


'use strict';

$(window).on('load', function() {
	/*------------------
		Preloder
	--------------------*/
	$(".loader").fadeOut();
	$("#preloder").delay(400).fadeOut("slow");

});

(function($) {
	/*------------------
		Navigation
	--------------------*/
	$(".main-menu").slicknav({
        appendTo: '.header-section',
        allowParentLinks: true
    });

	/*------------------
		Background Set
	--------------------*/
	$('.set-bg').each(function() {
		var bg = $(this).data('setbg');
		$(this).css('background-image', 'url(' + bg + ')');
	});
	
	/*------------------
		Hero Slider
	--------------------*/
	var $slider = $('.hero-slider');
	var SLIDER_TIMEOUT = 10000;

	$slider.owlCarousel({
		items: 1,
		nav: false,
		dots: false,
		autoplay: true,
		autoplayTimeout: SLIDER_TIMEOUT,
		animateOut: 'fadeOut',
   		animateIn: 'fadeIn',
		loop: true,
		onInitialized: ({target}) => {
			var animationStyle = '-webkit-animation-duration'+ SLIDER_TIMEOUT +'ms;animation-duration:'+ SLIDER_TIMEOUT+'ms';
			var progressBar = $('<div class="slider-progress-bar"><span class="progress" style='+ animationStyle +'></span></div>');
			$(target).append(progressBar);
		},
		onChanged: ({type, target}) => {
			if (type === 'changed') {
				var $progressBar = $(target).find('.slider-progress-bar');
				var clonedProgressBar = $progressBar.clone(true);

				$progressBar.remove();
				$(target).append(clonedProgressBar);
			}
		}
	});

	/*------------------
		Video Popup
	--------------------*/
	$('.video-play').magnificPopup({
		type: 'iframe'
	});

	/*------------------
		Testimonials
	--------------------*/
	$('.testimonial-slider').owlCarousel({
		items: 1,
		nav: false,
		dots: true,
		autoplay: true,
		loop: true,
		autoplayHoverPause: true,
		animateOut: 'slideOutDown',
   		animateIn: 'slideInDown',
	});

	/*------------------
		Circle progress
	--------------------*/
	$('.circle-progress').each(function() {
		var cpvalue = $(this).data("cpvalue");
		var cpcolor = $(this).data("cpcolor");
		var cpid 	= $(this).data("cpid");

		$(this).append('<div class="'+ cpid +'"></div><div class="progress-value"><h3>'+ cpvalue +'%</h3></div>');

		if (cpvalue < 100) {

			$('.' + cpid).circleProgress({
				value: '0.' + cpvalue,
				size: 80,
				thickness: 4,
				fill: cpcolor,
				emptyFill: "rgba(0, 0, 0, 0)"
			});
		} else {
			$('.' + cpid).circleProgress({
				value: 1,
				size: 80,
				thickness: 4,
				fill: cpcolor,
				emptyFill: "rgba(0, 0, 0, 0)"
			});
		}
	});

	/*------------------
		Back to Top
	--------------------*/
	$('body').append('<div id="backTotop"><i class="fa fa-angle-up"></i></div>');

	$(window).scroll(function() {
		if ($(this).scrollTop() > 400) {
			$('#backTotop').addClass('show');
		} else {
			$('#backTotop').removeClass('show');
		}
	});

	$('#backTotop').click(function() {
		$('html, body').animate({scrollTop : 0}, 800);
		return false;
	});

	/*------------------
		Scroll Reveal
	--------------------*/
	// Add .reveal class to candidates for animation
	$('.characters-box, .work-step-box, .ecosystem-card, .contact-card, .about-game, .about-img-box, .section-title, .game-title').addClass('reveal');

	function revealOnScroll() {
		var windowHeight = $(window).height();
		$('.reveal').each(function() {
			var elementTop = $(this).offset().top;
			var scrollPos = $(window).scrollTop();
			
			if (elementTop < scrollPos + windowHeight - 50) {
				$(this).addClass('active');
			}
		});
	}

	$(window).on('scroll load', revealOnScroll);

	/*------------------
		Smooth Scroll
	--------------------*/
	$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				event.preventDefault();
				$('html, body').animate({
					scrollTop: target.offset().top - 80
				}, 1000);
			}
		}
	});

})(jQuery);
