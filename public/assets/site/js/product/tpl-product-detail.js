const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

var mzOptions = {
    textExpandHint: "برای بزرگنمایی کلیک کنید",
    textHoverZoomHint: "",
    zoomMode: "magnifier",
    zoomWidth: 200,
    zoomHeight: 200,
    transitionEffect: false,
};
var swiper = new Swiper(".swiper-selector", {
    spaceBetween: 30,
    slidesPerView: 4,
    grabCursor: true,
    autoplay: {
        delay: 1500,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        0: {
            slidesPerView: 4,
            spaceBetween: 10,
        },
        576: {
            slidesPerView: 4.5,
            spaceBetween: 15,
        },
        768: {
            slidesPerView: 8,
            spaceBetween: 10,
        },
        992: {
            slidesPerView: 4,
            spaceBetween: 10,
        },
        1200: {
            slidesPerView: 4,
            spaceBetween: 10,
        },
        1400: {
            slidesPerView: 3.75,
            spaceBetween: 10,
        },
        1600: {
            slidesPerView: 4.5,
            spaceBetween: 10,
        },
    },
});
const pdpRelatedSwiperOptions = {
    spaceBetween: 30,
    slidesPerView: 5.75,
    grabCursor: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 1.25,
            spaceBetween: 10,
        },
        576: {
            slidesPerView: 2.75,
            spaceBetween: 15,
        },
        768: {
            slidesPerView: 3.75,
            spaceBetween: 30,
        },
        992: {
            slidesPerView: 4,
            spaceBetween: 30,
        },
        1200: {
            slidesPerView: 4.75,
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
};

document.querySelectorAll(".pdp-related-swiper, .swiper-team").forEach((el) => {
    new Swiper(el, pdpRelatedSwiperOptions);
});

function initFeatureListToggle() {
    const toggleBtn = document.getElementById("featureButton");
    const listItems = document.querySelectorAll("#feature-list li");
    if (!toggleBtn || !listItems.length) return;

    const isDesktop = window.matchMedia("(min-width: 992px)").matches;
    if (isDesktop || listItems.length <= 5) {
        toggleBtn.classList.add("d-none");
        listItems.forEach((item) => {
            item.classList.remove("d-none");
        });
        return;
    }

    listItems.forEach((item, index) => {
        if (index >= 5) {
            item.classList.add("d-none");
        } else {
            item.classList.remove("d-none");
        }
    });
    toggleBtn.classList.remove("d-none");
}

let expanded = false;

function toggleFeatures() {
    const listItems = document.querySelectorAll("#feature-list li");
    const toggleBtn = document.getElementById("featureButton");
    if (!toggleBtn || window.matchMedia("(min-width: 992px)").matches) return;

    expanded = !expanded;
    toggleBtn.setAttribute("aria-expanded", expanded ? "true" : "false");

    listItems.forEach((item, index) => {
        if (!expanded && index >= 5) {
            item.classList.add("d-none");
        } else {
            item.classList.remove("d-none");
        }
    });

    toggleBtn.innerHTML = expanded
        ? '<i class="bi bi-chevron-up d-flex" aria-hidden="true"></i> مشاهده کمتر'
        : '<i class="bi bi-chevron-down d-flex" aria-hidden="true"></i> مشاهده بیشتر';
}

document.addEventListener("DOMContentLoaded", initFeatureListToggle);
window.addEventListener("resize", initFeatureListToggle);

var swiper = new Swiper(".mySwiper-mobile-detail", {
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
});
var swiper = new Swiper(".mySwiper-images", {
    slidesPerView: "auto",
    loop: false,
    spaceBetween: 10,
    freeMode: true,
    watchSlidesProgress: true,
});
var swiper2 = new Swiper(".mySwiper-images-2", {
    loop: true,
    spaceBetween: 10,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    thumbs: {
        swiper: swiper,
    },
});

document.addEventListener("DOMContentLoaded", function () {
    const text = document.getElementById("auctionText");
    if (!text) return;
    text.classList.add("show");

    setTimeout(() => {
        text.classList.remove("show");
    }, 3000);
});
