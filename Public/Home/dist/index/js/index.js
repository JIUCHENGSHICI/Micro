			var galleryTop = new Swiper('.gallery-top', {
				spaceBetween: 10,
				onTransitionStart: function(swiper) {
					var id = swiper.activeIndex;
					console.log(id)
					$("#topNav  .active").removeClass('active');
					$("[data-index='" + (id + 1) + "']").addClass("active");
					mySwiper.slideTo(id-1, 500);

				}

			});
			//			var galleryThumbs = new Swiper('.gallery-thumbs', {
			//				spaceBetween: 10,
			//				centeredSlides: true,
			//				slidesPerView: 'auto',
			//				touchRatio: 0.2,
			//				slideToClickedSlide: true,
			//				//				====
			////				slidesPerView: 4,
			////				spaceBetween: 30,
			////				direction: 'horizontal',
			////				grabCursor: true,
			////				width: 70,
			////				spaceBetween: 10,
			//
			//			});
			//			
			var mySwiper = new Swiper('#topNav', {
				freeMode: true,
				freeModeMomentumRatio: 0.5,
				slidesPerView: 'auto',
				//==================
				//				spaceBetween: 10,
//								centeredSlides: true,
				//				slidesPerView: 'auto',
				//				touchRatio: 0.2,
				//				slideToClickedSlide: true,
				//				====
				//				slidesPerView: 4,
				//				spaceBetween: 30,
				//				direction: 'horizontal',
//								grabCursor: true,
//								width: 70,
//								spaceBetween: 10,

			});
			var swiperWidth = mySwiper.container[0].clientWidth;
			var maxTranslate = mySwiper.maxTranslate();
			var maxWidth = -maxTranslate + swiperWidth / 2;

			$(".swiper-container").on('touchstart', function(e) {
				//				e.preventDefault()
			})
			$("#topNav .swiper-slide").on('click', function(e) {
				//				e.preventDefault();
				var id = $(this).attr("data-index");
				galleryTop.slideTo(id - 1, 0);
			});
			mySwiper.on('tap', function(swiper, e) {

				//				e.preventDefault();

				slide = swiper.slides[swiper.clickedIndex]
				slideLeft = slide.offsetLeft
				slideWidth = slide.clientWidth
				slideCenter = slideLeft + slideWidth / 2
				// 被点击slide的中心点

				mySwiper.setWrapperTransition(300)

				if(slideCenter < swiperWidth / 2) {

					mySwiper.setWrapperTranslate(0)

				} else if(slideCenter > maxWidth) {

					mySwiper.setWrapperTranslate(maxTranslate)

				} else {

					nowTlanslate = slideCenter - swiperWidth / 2

					mySwiper.setWrapperTranslate(-nowTlanslate)

				}

				$("#topNav  .active").removeClass('active')

				$("#topNav .swiper-slide").eq(swiper.clickedIndex).addClass('active')

			})
			//			galleryTop.params.control = mySwiper;
			//			mySwiper.params.control = galleryTop;