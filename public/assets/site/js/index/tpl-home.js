/**
 * Hide homepage section arrows when slides fit without scrolling.
 * Uses Swiper lock + snapGrid so resize/breakpoints stay in sync.
 */
function bindHomeSwiperNav(section, swiper, navSelector) {
    var nav = section.querySelector(navSelector);
    if (!nav) {
        return;
    }
    function syncNav() {
        var needsNav =
            !swiper.isLocked &&
            Array.isArray(swiper.snapGrid) &&
            swiper.snapGrid.length > 1;
        nav.hidden = !needsNav;
        nav.classList.toggle("is-nav-hidden", !needsNav);
        if (!needsNav && swiper.autoplay) {
            swiper.autoplay.stop();
        }
    }
    syncNav();
    swiper.on("lock", syncNav);
    swiper.on("unlock", syncNav);
    swiper.on("resize", syncNav);
    swiper.on("breakpoint", syncNav);
    swiper.on("update", syncNav);
    swiper.on("observerUpdate", syncNav);
}

function homeSwiperNeedsNav(swiper) {
    return (
        !swiper.isLocked &&
        Array.isArray(swiper.snapGrid) &&
        swiper.snapGrid.length > 1
    );
}

if (document.querySelector(".mySwiper-blogsnew")) {
    var swiper = new Swiper(".mySwiper-blogsnew", {
        spaceBetween: 13,
        slidesPerView: 3,
        watchSlidesProgress: true,
        navigation: {
            nextEl: ".swiper-button-next2",
            prevEl: ".swiper-button-prev2",
        },
        breakpoints: {
            0: {
                slidesPerView: 1.5,
                spaceBetween: 10,
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            992: {
                slidesPerView: 2.5,
                spaceBetween: 10,
            },
            1200: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
            1400: {
                slidesPerView: 3,
                spaceBetween: 10,
            },
        },
    });
}
if (document.querySelector("[data-brands-swiper]")) {
    var brandsReduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    document.querySelectorAll("[data-brands-swiper]").forEach(function (section) {
        var el = section.querySelector(".swiper-brands");
        if (!el) {
            return;
        }
        var brandsSwiper = new Swiper(el, {
            rtl: true,
            slidesPerView: 6,
            spaceBetween: 12,
            grabCursor: true,
            watchOverflow: true,
            rewind: true,
            observer: true,
            observeParents: true,
            autoplay: brandsReduceMotion
                ? false
                : {
                      delay: 2800,
                      disableOnInteraction: false,
                      pauseOnMouseEnter: true,
                  },
            navigation: {
                nextEl: section.querySelector(".brands-nav__btn--next"),
                prevEl: section.querySelector(".brands-nav__btn--prev"),
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                576: {
                    slidesPerView: 3,
                    spaceBetween: 12,
                },
                768: {
                    slidesPerView: 4,
                    spaceBetween: 12,
                },
                992: {
                    slidesPerView: 5,
                    spaceBetween: 12,
                },
                1200: {
                    slidesPerView: 6,
                    spaceBetween: 12,
                },
            },
        });
        bindHomeSwiperNav(section, brandsSwiper, ".brands-nav");
        if (!brandsReduceMotion && brandsSwiper.autoplay) {
            section.addEventListener("focusin", function () {
                brandsSwiper.autoplay.stop();
            });
            section.addEventListener("focusout", function (event) {
                if (!section.contains(event.relatedTarget) && homeSwiperNeedsNav(brandsSwiper)) {
                    brandsSwiper.autoplay.start();
                }
            });
        }
    });
}
if (document.querySelector("[data-services-swiper]")) {
    var servicesReduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    document.querySelectorAll("[data-services-swiper]").forEach(function (section) {
        var el = section.querySelector(".swiper-services");
        if (!el) {
            return;
        }
        var servicesSwiper = new Swiper(el, {
            rtl: true,
            slidesPerView: 3,
            spaceBetween: 16,
            grabCursor: true,
            watchOverflow: true,
            rewind: true,
            observer: true,
            observeParents: true,
            autoplay: servicesReduceMotion
                ? false
                : {
                      delay: 3200,
                      disableOnInteraction: false,
                      pauseOnMouseEnter: true,
                  },
            navigation: {
                nextEl: section.querySelector(".services-nav__btn--next"),
                prevEl: section.querySelector(".services-nav__btn--prev"),
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 12,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
            },
        });
        bindHomeSwiperNav(section, servicesSwiper, ".services-nav");
        if (!servicesReduceMotion && servicesSwiper.autoplay) {
            section.addEventListener("focusin", function () {
                servicesSwiper.autoplay.stop();
            });
            section.addEventListener("focusout", function (event) {
                if (!section.contains(event.relatedTarget) && homeSwiperNeedsNav(servicesSwiper)) {
                    servicesSwiper.autoplay.start();
                }
            });
        }
    });
}
if (document.querySelector("[data-blogs-swiper]")) {
    var blogsReduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    document.querySelectorAll("[data-blogs-swiper]").forEach(function (section) {
        var el = section.querySelector(".swiper-blogs");
        if (!el) {
            return;
        }
        var blogsSwiper = new Swiper(el, {
            rtl: true,
            slidesPerView: 3,
            spaceBetween: 16,
            grabCursor: true,
            watchOverflow: true,
            rewind: true,
            observer: true,
            observeParents: true,
            autoplay: blogsReduceMotion
                ? false
                : {
                      delay: 3200,
                      disableOnInteraction: false,
                      pauseOnMouseEnter: true,
                  },
            navigation: {
                nextEl: section.querySelector(".blogs-nav__btn--next"),
                prevEl: section.querySelector(".blogs-nav__btn--prev"),
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 12,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
            },
        });
        bindHomeSwiperNav(section, blogsSwiper, ".blogs-nav");
        if (!blogsReduceMotion && blogsSwiper.autoplay) {
            section.addEventListener("focusin", function () {
                blogsSwiper.autoplay.stop();
            });
            section.addEventListener("focusout", function (event) {
                if (!section.contains(event.relatedTarget) && homeSwiperNeedsNav(blogsSwiper)) {
                    blogsSwiper.autoplay.start();
                }
            });
        }
    });
}
if (document.querySelector(".services .mySwiper2")) {
    var servicesThumbsEl = document.querySelector(".services .mySwiper");
    var servicesMainEl = document.querySelector(".services .mySwiper2");
    var servicesReduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var servicesThumbs = null;
    var servicesSection = document.querySelector(".services");

    if (servicesThumbsEl) {
        servicesThumbs = new Swiper(servicesThumbsEl, {
            direction: "vertical",
            slidesPerView: "auto",
            spaceBetween: 6,
            watchSlidesProgress: true,
            watchOverflow: true,
            slideToClickedSlide: true,
            navigation: {
                nextEl: servicesSection.querySelector(".services-dock__btn--next"),
                prevEl: servicesSection.querySelector(".services-dock__btn--prev"),
            },
            breakpoints: {
                0: {
                    direction: "horizontal",
                    slidesPerView: "auto",
                    spaceBetween: 8,
                },
                992: {
                    direction: "vertical",
                    slidesPerView: "auto",
                    spaceBetween: 6,
                },
            },
        });
        bindHomeSwiperNav(servicesSection, servicesThumbs, ".services-dock__nav");
    }

    new Swiper(servicesMainEl, {
        effect: "fade",
        fadeEffect: {
            crossFade: true,
        },
        speed: servicesReduceMotion ? 0 : 280,
        allowTouchMove: true,
        watchOverflow: true,
        thumbs: servicesThumbs
            ? {
                  swiper: servicesThumbs,
              }
            : undefined,
    });
}
if (document.querySelector(".mySwiper-samples")) {
    var swiper = new Swiper(".mySwiper-samples", {
        loop: true,
        spaceBetween: 13,
        slidesPerView: 6,
        freeMode: true,
        watchSlidesProgress: true,

        breakpoints: {
            0: {
                slidesPerView: 1.5,
                spaceBetween: 15,
            },
            576: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
        },
    });
}
if (document.querySelector(".swiper-team")) {
    var swiper = new Swiper(".swiper-team", {
        spaceBetween: 18,
        slidesPerView: 4.25,
        grabCursor: true,
        autoplay: window.matchMedia("(prefers-reduced-motion: reduce)").matches
            ? false
            : {
                  delay: 2800,
                  disableOnInteraction: false,
                  pauseOnMouseEnter: true,
              },
        breakpoints: {
            0: {
                slidesPerView: 1.35,
                spaceBetween: 12,
            },
            576: {
                slidesPerView: 2.15,
                spaceBetween: 14,
            },
            768: {
                slidesPerView: 2.75,
                spaceBetween: 16,
            },
            992: {
                slidesPerView: 3.25,
                spaceBetween: 18,
            },
            1200: {
                slidesPerView: 3.75,
                spaceBetween: 18,
            },
            1400: {
                slidesPerView: 4.25,
                spaceBetween: 18,
            },
            1600: {
                slidesPerView: 4.75,
                spaceBetween: 18,
            },
        },
    });
}
const honorsSlider = document.querySelector(".swiper-honors");
let honorsSwiper;
let lastSlideIndex = 0;

if (honorsSlider) {
    honorsSwiper = new Swiper(honorsSlider, {
        slidesPerView: 4,
        spaceBetween: 8,
        centeredSlides: true,
        grabCursor: true,
        autoplay: window.matchMedia("(prefers-reduced-motion: reduce)").matches
            ? false
            : {
                  delay: 3200,
                  disableOnInteraction: false,
                  pauseOnMouseEnter: true,
              },
        breakpoints: {
            0: { slidesPerView: 1.25 },
            576: { slidesPerView: 2 },
            768: { slidesPerView: 2.5 },
            992: { slidesPerView: 3.25 },
            1200: { slidesPerView: 4 },
        },
    });
}

if (document.querySelector(".swiper-package")) {
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
                spaceBetween: 15,
            },
            1200: {
                slidesPerView: 2,
                spaceBetween: 15,
            },
            1400: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            1600: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
        },
    });
}
if (document.querySelector(".swiper-categories")) {
    var swiper = new Swiper(".swiper-categories", {
        observer: true,
        observeParents: true,
        spaceBetween: 30,
        slidesPerView: 3.5,
        grabCursor: true,
        loop: true,
        autoplay: window.matchMedia("(prefers-reduced-motion: reduce)").matches
            ? false
            : {
                  delay: 2800,
                  disableOnInteraction: true,
                  pauseOnMouseEnter: true,
              },
        breakpoints: {
            0: {
                slidesPerView: 1.5,
                spaceBetween: 10,
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
                spaceBetween: 15,
            },
            1200: {
                slidesPerView: 3,
                spaceBetween: 15,
            },
            1400: {
                slidesPerView: 2.75,
                spaceBetween: 15,
            },
            1600: {
                slidesPerView: 3.5,
                spaceBetween: 15,
            },
        },
    });
}
if (document.querySelector("[data-offer-swiper]")) {
    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    document.querySelectorAll("[data-offer-swiper]").forEach(function (section) {
        var el = section.querySelector(".swiper-offer-new, .swiper-offer");
        if (!el) {
            return;
        }
        var offerSwiper = new Swiper(el, {
            rtl: true,
            slidesPerView: 4,
            spaceBetween: 16,
            grabCursor: true,
            watchOverflow: true,
            observer: true,
            observeParents: true,
            autoplay: reduceMotion
                ? false
                : {
                      delay: 2800,
                      disableOnInteraction: false,
                      pauseOnMouseEnter: true,
                  },
            navigation: {
                nextEl: section.querySelector(".offer-nav__btn--next"),
                prevEl: section.querySelector(".offer-nav__btn--prev"),
            },
            breakpoints: {
                0: {
                    slidesPerView: 2,
                    spaceBetween: 10,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 16,
                },
            },
        });
        bindHomeSwiperNav(section, offerSwiper, ".offer-nav");
    });
}


document.addEventListener("show.bs.modal", () => {
    if (!honorsSwiper) return;

    lastSlideIndex = honorsSwiper.activeIndex;

    if (honorsSwiper.autoplay) {
        honorsSwiper.autoplay.stop();
    }
});

document.addEventListener("hidden.bs.modal", () => {
    if (!honorsSwiper) return;

    honorsSwiper.slideTo(lastSlideIndex, 0, false);

    honorsSwiper.update();

    if (honorsSwiper.autoplay) {
        honorsSwiper.autoplay.start();
    }
});

(function () {
    var section = document.querySelector(".products[data-product-tabs]");
    if (!section) {
        return;
    }
    var tablist = section.querySelector('[role="tablist"]');
    var tabs = tablist ? Array.prototype.slice.call(tablist.querySelectorAll('[role="tab"]')) : [];
    var panel = section.querySelector("#home-products-grid");
    var items = section.querySelectorAll(".products-grid__item");
    var empty = section.querySelector(".products-empty");
    if (!tablist || !tabs.length) {
        return;
    }

    function applyFilter(tab) {
        var filter = tab.getAttribute("data-filter") || "all";
        var visible = 0;
        tabs.forEach(function (btn) {
            var on = btn === tab;
            btn.classList.toggle("is-active", on);
            btn.setAttribute("aria-selected", on ? "true" : "false");
            btn.tabIndex = on ? 0 : -1;
        });
        if (panel) {
            panel.setAttribute("aria-labelledby", tab.id);
        }
        items.forEach(function (item) {
            var show = filter === "all";
            if (!show) {
                var cats = (item.getAttribute("data-root-cats") || "").split(",");
                show = cats.indexOf(String(filter)) !== -1;
            }
            item.hidden = !show;
            if (show) {
                visible += 1;
            }
        });
        if (empty) {
            empty.hidden = visible > 0;
        }
    }

    tablist.addEventListener("click", function (event) {
        var tab = event.target.closest('[role="tab"]');
        if (!tab || !tablist.contains(tab)) {
            return;
        }
        applyFilter(tab);
    });

    tablist.addEventListener("keydown", function (event) {
        if (event.key !== "ArrowLeft" && event.key !== "ArrowRight" && event.key !== "Home" && event.key !== "End") {
            return;
        }
        var index = tabs.indexOf(document.activeElement);
        if (index < 0) {
            return;
        }
        event.preventDefault();
        var rtl = getComputedStyle(tablist).direction === "rtl";
        if (event.key === "Home") {
            index = 0;
        } else if (event.key === "End") {
            index = tabs.length - 1;
        } else if (event.key === "ArrowRight") {
            index = rtl ? index - 1 : index + 1;
        } else {
            index = rtl ? index + 1 : index - 1;
        }
        index = (index + tabs.length) % tabs.length;
        tabs[index].focus();
        applyFilter(tabs[index]);
    });
})();

if (document.querySelector(".mySwiper-boxHonors")) {
    var swiper = new Swiper(".mySwiper-boxHonors", {
        spaceBetween: 15,
        autoplay: window.matchMedia("(prefers-reduced-motion: reduce)").matches
            ? false
            : {
                  delay: 2500,
                  disableOnInteraction: false,
              },
        breakpoints: {
            0: { slidesPerView: 1.5 },
            576: { slidesPerView: 2 },
            768: { slidesPerView: 2.5 },
            992: { slidesPerView: 3.25 },
            1200: { slidesPerView: 4 },
            1400: { slidesPerView: 4.2 },
        },
    });
}