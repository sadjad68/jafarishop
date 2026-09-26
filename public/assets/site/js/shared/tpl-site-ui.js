// vertical or horizontal logo
if (document.querySelector(".logo-menu")) {
    function checkLogo() {
        const logo = document.querySelectorAll(".logo-menu");

        logo.forEach((logoItem) => {
            const widthLogo = logoItem.naturalWidth;
            const heightLogo = logoItem.naturalHeight;
            if (widthLogo < heightLogo) {
                logoItem.classList.add("vertical");
            } else if (widthLogo === heightLogo) {
                logoItem.classList.add("square");
            }
        });
    }
    window.addEventListener("load", checkLogo());
}

// scrool to top button
if (document.getElementById("scroll-to-top")) {
    $(document).ready(function () {
        $(window).scroll(function () {
            $(this).scrollTop() > 500
                ? $("#scroll-to-top").fadeIn()
                : $("#scroll-to-top").fadeOut();
        }),
            $("#scroll-to-top").click(function () {
                return $("html, body").animate({ scrollTop: 0 }, 600), !1;
            });
    });
}

// light or dark logo (overlay header). Theme1 keeps a solid header, so skip the swap.
if (
    !document.body.classList.contains("theme-theme1") &&
    (document.querySelector(".logo-menu") ||
        document.getElementById("lightLogo"))
) {
    $(function () {
        var logoLight = document.querySelector(".logo-menu");
        var logoDark = document.getElementById("lightLogo");
        var header = $(".menu");
        $(window).scroll(function () {
            if ($(window).scrollTop() >= 150) {
                header.addClass("menu-fixed");
                logoLight.classList.add("d-none");
                logoDark.classList.remove("d-none");
            } else {
                header.removeClass("menu-fixed");
                logoLight.classList.remove("d-none");
                logoDark.classList.add("d-none");
            }
        });
    });
}

jQuery(document).ready(function ($) {
    $('#v-pills-tab[data-mouse="hover"] button').hover(function () {
        $(this).tab("show");
    });
    $('button[data-bs-toggle="pill"]').on("shown.bs.tab", function (e) {
        var target = $(e.relatedTarget).attr("href");
        $(target).removeClass("active");
    });
});

const imageComparisonSliders = document.querySelectorAll(
    '[data-component="image-comparison-slider"]'
);

function setSliderstate(e, element) {
    const sliderRange = element.querySelector("[data-image-comparison-range]");

    if (e.type === "input") {
        sliderRange.classList.add("image-comparison__range--active");
        return;
    }

    sliderRange.classList.remove("image-comparison__range--active");
    element.removeEventListener("mousemove", moveSliderThumb);
}

function layoutComparisonOverlay(element) {
    const wrap = element.querySelector(".image-comparison__slider-wrapper");
    const overlay = element.querySelector("[data-image-comparison-overlay]");

    if (!wrap || !overlay) {
        return;
    }

    const width = wrap.clientWidth + "px";
    const height = wrap.clientHeight + "px";

    overlay
        .querySelectorAll(
            ".image-comparison__figure, .image-comparison__picture, .image-comparison__image"
        )
        .forEach(function (node) {
            node.style.width = width;
            node.style.height = height;
            node.style.maxWidth = "none";
        });
}

function moveSliderThumb(e) {
    const sliderRange = e.currentTarget.querySelector(
        "[data-image-comparison-range]"
    );
    const thumb = e.currentTarget.querySelector(
        "[data-image-comparison-thumb]"
    );
    let position = e.layerY - 20;

    if (!sliderRange || !thumb) {
        return;
    }

    if (e.layerY <= sliderRange.offsetTop) {
        position = -20;
    }

    if (e.layerY >= sliderRange.offsetHeight) {
        position = sliderRange.offsetHeight - 20;
    }

    thumb.style.top = `${position}px`;
}

function moveSliderRange(e, element) {
    const value = e.target.value;
    const slider = element.querySelector("[data-image-comparison-slider]");
    const imageWrapperOverlay = element.querySelector(
        "[data-image-comparison-overlay]"
    );

    if (slider) {
        slider.style.left = `${value}%`;
    }

    if (imageWrapperOverlay) {
        imageWrapperOverlay.style.width = `${value}%`;
    }

    layoutComparisonOverlay(element);
    element.addEventListener("mousemove", moveSliderThumb);
    setSliderstate(e, element);
}

function init(element) {
    const sliderRange = element.querySelector("[data-image-comparison-range]");

    if (!sliderRange) {
        return;
    }

    layoutComparisonOverlay(element);

    if ("ontouchstart" in window === false) {
        sliderRange.addEventListener("mouseup", (e) =>
            setSliderstate(e, element)
        );
        element.addEventListener("mousemove", moveSliderThumb);
    }

    sliderRange.addEventListener("input", (e) => moveSliderRange(e, element));
    sliderRange.addEventListener("change", (e) => moveSliderRange(e, element));

    if (typeof ResizeObserver !== "undefined") {
        const wrap = element.querySelector(".image-comparison__slider-wrapper");
        if (wrap) {
            new ResizeObserver(function () {
                layoutComparisonOverlay(element);
            }).observe(wrap);
        }
    } else {
        window.addEventListener("resize", function () {
            layoutComparisonOverlay(element);
        });
    }
}

imageComparisonSliders.forEach(init);

var swiper = new Swiper(".swiper-suggest", {
    spaceBetween: 8,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
    breakpoints: {
        0: {
            slidesPerView: 3.25,
            spaceBetween: 8,
        },
        576: {
            slidesPerView: 4,
            spaceBetween: 8,
        },
        768: {
            slidesPerView: 4,
            spaceBetween: 8,
        },
        992: {
            slidesPerView: 2.75,
            spaceBetween: 8,
        },
        1200: {
            slidesPerView: 3.75,
            spaceBetween: 8,
        },
        1400: {
            slidesPerView: 4.75,
            spaceBetween: 8,
        },
        1600: {
            slidesPerView: 6,
            spaceBetween: 8,
        },
    },
});

if (document.getElementById("text-box")) {
    document.addEventListener("DOMContentLoaded", function () {
        const textBox = document.getElementById("text-box");
        const moreButton = document.getElementById("more-button");

        if (textBox.clientHeight < 400) {
            moreButton.style.display = "none";
            textBox.classList.remove("after");
        } else {
            moreButton.style.display = "flex";
            textBox.classList.add("after");
        }
    });
}

if (document.querySelectorAll(".tabs .description iframe")) {
    const iframes = document.querySelectorAll(".tabs .description iframe");
    iframes.forEach((iframe) => {
        iframe.outerHTML = `<div class="iframe-parent"><span style="padding-top: 57%;display: block"></span>${iframe.outerHTML}</div>`;
    });
}
if (document.querySelectorAll(".description-box iframe")) {
    const iframes = document.querySelectorAll(".description-box iframe");
    iframes.forEach((iframe) => {
        iframe.outerHTML = `<div class="iframe-parent"><span style="padding-top: 57%;display: block"></span>${iframe.outerHTML}</div>`;
    });
}

if (document.querySelectorAll(".seo-box iframe")) {
    const iframes = document.querySelectorAll(".seo-box iframe");
    iframes.forEach((iframe) => {
        iframe.outerHTML = `<div class="iframe-parent"><span style="padding-top: 57%;display: block"></span>${iframe.outerHTML}</div>`;
    });
}

(function () {
    function initFooterAboutCollapse(prefix) {
        var about = document.getElementById(prefix + "-footer-about");
        var text = document.getElementById(prefix + "-footer-about-text");
        var btn = document.getElementById(prefix + "-footer-collapse-btn");
        var label = document.getElementById(prefix + "-footer-collapse-label");
        if (!about || !text || !btn || !label) {
            return;
        }

        function hasOverflow() {
            var width = text.clientWidth || text.offsetWidth || about.clientWidth;
            if (!width) {
                return text.textContent.replace(/\s+/g, " ").trim().length > 140;
            }

            var clone = text.cloneNode(true);
            clone.removeAttribute("id");
            clone.classList.remove("is-clamped");
            clone.style.cssText = "position:absolute;left:-9999px;top:0;height:auto;max-height:none;display:block;-webkit-line-clamp:unset;line-clamp:unset;-webkit-box-orient:unset;overflow:visible;white-space:pre-line;visibility:hidden;";
            clone.style.width = width + "px";
            about.appendChild(clone);
            var full = clone.scrollHeight;
            about.removeChild(clone);

            var styles = window.getComputedStyle(text);
            var lineHeight = parseFloat(styles.lineHeight);
            if (!lineHeight) {
                var fontSize = parseFloat(styles.fontSize) || 13;
                lineHeight = fontSize * 1.9;
            }
            return full - lineHeight * 3 > 2;
        }

        function syncFooterCollapse() {
            var expanded = about.classList.contains("is-expanded");
            if (expanded) {
                text.classList.remove("is-clamped");
                btn.hidden = false;
                btn.setAttribute("aria-expanded", "true");
                label.textContent = "مشاهده کمتر";
                return;
            }

            text.classList.add("is-clamped");
            var overflow = hasOverflow();
            if (!overflow) {
                text.classList.remove("is-clamped");
            }
            about.classList.toggle("is-clamped", overflow);
            btn.hidden = !overflow;
            btn.setAttribute("aria-expanded", "false");
            label.textContent = "مشاهده بیشتر";
        }

        btn.addEventListener("click", function () {
            var open = about.classList.toggle("is-expanded");
            btn.classList.toggle("is-open", open);
            syncFooterCollapse();
        });

        window.addEventListener("resize", function () {
            if (!about.classList.contains("is-expanded")) {
                syncFooterCollapse();
            }
        });

        syncFooterCollapse();
        window.requestAnimationFrame(syncFooterCollapse);
        window.setTimeout(syncFooterCollapse, 50);
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(syncFooterCollapse);
        }
        window.addEventListener("load", syncFooterCollapse);
    }

    initFooterAboutCollapse("t1");
    initFooterAboutCollapse("t2");
})();
