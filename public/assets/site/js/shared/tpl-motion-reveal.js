/**
 * Lightweight, dependency-free scroll motion for the Shopika (theme2) storefront.
 * - Adds `.js-motion` to <html> so CSS reveal states only ever activate when this
 *   script actually runs (progressive enhancement, no FOUC / hidden content risk).
 * - Reveals any [data-reveal] element via IntersectionObserver.
 * - Adds a `.scrolled-deep` class after a bigger scroll distance for header polish.
 * Respects prefers-reduced-motion.
 */
(function () {
    "use strict";

    var root = document.documentElement;
    var prefersReduced = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    root.classList.add("js-motion");

    if (prefersReduced || typeof IntersectionObserver === "undefined") {
        document.querySelectorAll("[data-reveal]").forEach(function (el) {
            el.classList.add("is-visible");
        });
        return;
    }

    var observer = new IntersectionObserver(
        function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: "0px 0px -8% 0px" }
    );

    function observeAll() {
        document.querySelectorAll("[data-reveal]:not(.is-visible)").forEach(function (el) {
            observer.observe(el);
        });
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", observeAll);
    } else {
        observeAll();
    }

    // Re-scan for elements injected later (e.g. Vue-rendered search/basket widgets).
    var mo = new MutationObserver(function () {
        observeAll();
    });
    document.addEventListener("DOMContentLoaded", function () {
        mo.observe(document.body, { childList: true, subtree: true });
    });

    var lastDeep = false;
    window.addEventListener(
        "scroll",
        function () {
            var deep = window.pageYOffset > 80;
            if (deep !== lastDeep) {
                document.body.classList.toggle("scrolled-deep", deep);
                lastDeep = deep;
            }
        },
        { passive: true }
    );
})();
