var swiper = new Swiper(".mySwiper", {
  loop: true,
  spaceBetween: 13,
  slidesPerView: 6,
  freeMode: true,
  watchSlidesProgress: true,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    0: {
      slidesPerView: 2,
      spaceBetween: 13,
    },
    576: {
      slidesPerView: 2.5,
      spaceBetween: 13,
    },
    768: {
      slidesPerView: 4,
      spaceBetween: 13,
    },
    992: {
      slidesPerView: 4,
      spaceBetween: 13,
    },
    1200: {
      slidesPerView: 5,
      spaceBetween: 13,
    },
    1600: {
      slidesPerView: 6,
      spaceBetween: 13,
    },
  },
});
var swiper = new Swiper(".swiper-team", {
  spaceBetween: 30,
  slidesPerView: 5.75,
  grabCursor: true,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  breakpoints: {
    0: {
      slidesPerView: 1.25,
      spaceBetween: 15,
    },
    576: {
      slidesPerView: 1.75,
      spaceBetween: 15,
    },
    768: {
      slidesPerView: 2.75,
      spaceBetween: 15,
    },
    992: {
      slidesPerView: 3,
      spaceBetween: 30,
    },
    1200: {
      slidesPerView: 3.25,
      spaceBetween: 30,
    },
    1400: {
      slidesPerView: 5,
      spaceBetween: 30,
    },
    1600: {
      slidesPerView: 5.75,
      spaceBetween: 30,
    },
  },
});
var swiper = new Swiper(".swiper-package", {
  loop: true,
  spaceBetween: 30,
  slidesPerView: 4,
  grabCursor: true,
  autoplay: {
    delay: 1500,
    disableOnInteraction: false,
  },
  breakpoints: {
    0: {
      slidesPerView: 1.25,
      spaceBetween: 15,
    },
    576: {
      slidesPerView: 2,
      spaceBetween: 15,
    },
    768: {
      slidesPerView: 2.5,
      spaceBetween: 15,
    },
    992: {
      slidesPerView: 3,
      spaceBetween: 30,
    },
    1200: {
      slidesPerView: 3,
      spaceBetween: 30,
    },
    1400: {
      slidesPerView: 3,
      spaceBetween: 30,
    },
    1600: {
      slidesPerView: 4,
      spaceBetween: 30,
    },
  },
});
var swiper = new Swiper(".mySwiper-thumb-sample", {
  spaceBetween: 10,
  slidesPerView: 4.5,
  freeMode: true,
  watchSlidesProgress: true,
});
var swiper2 = new Swiper(".mySwiper-sample", {
  spaceBetween: 10,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  thumbs: {
    swiper: swiper,
  },
});