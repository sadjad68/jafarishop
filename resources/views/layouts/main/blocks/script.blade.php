<script src="{{ asset('assets/site/js/shared/tpl-jquery.min.js') }}"></script>
<script src="{{ asset('assets/site/js/shared/tpl-bootstrap.bundle.min.js') }}"></script>
{{--<script src="{{ asset('assets/site/js/shared/bootstrap.popper.min.js') }}"></script>--}}
<script src="{{ asset('assets/site/js/shared/tpl-swiper-bundle.min.js?v0.01') }}"></script>
<script src="{{ asset('assets/site/js/shared/tpl-site-ui.js?v0.28') }}"></script>
<script src="{{ asset('assets/site/js/product/tpl-product-list.js') }}"></script>
@if (in_array($theme_provider->getValue(), ['theme1', 'theme2']))
    <script src="{{ asset('assets/site/js/shared/tpl-motion-reveal.js?v1.0') }}"></script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const wrapper = document.getElementById("videoWrapper");
        if (!wrapper) return;

        function moveAllAparatEmbeds() {
            const aparatEmbeds = wrapper.querySelectorAll(".iframe-parent");

            if (aparatEmbeds.length > 0) {
                const row = document.createElement("div");
                row.className = "row w-100 m-0";

                aparatEmbeds.forEach(embed => {
                    embed.dataset.moved = "true";

                    const col = document.createElement("div");
                    col.className = "col-md-6 col-12 p-2";

                    col.appendChild(embed);
                    row.appendChild(col);
                });

                wrapper.appendChild(row);
            }
        }

        moveAllAparatEmbeds();

        const observer = new MutationObserver(() => {
            moveAllAparatEmbeds();
        });

        observer.observe(wrapper, {
            childList: true,
            subtree: true
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!document.body.classList.contains('theme-theme1')) {
            return;
        }

        function t1PlaceFlyout(item) {
            var fly = item.querySelector(':scope > .t1-drop--flyout');
            if (!fly) {
                return;
            }
            var shown = window.getComputedStyle(fly).display !== 'none';
            if (!shown) {
                return;
            }
            var rect = item.getBoundingClientRect();
            var list = fly.querySelector(':scope > .t1-drop__list');
            fly.style.position = 'fixed';
            fly.style.inset = 'auto';
            fly.style.padding = '0';
            fly.style.zIndex = '80';
            var width = fly.offsetWidth || 240;
            var height = list
                ? Math.min(list.scrollHeight, window.innerHeight - 16)
                : 200;
            var left = rect.left - width + 4;
            if (left < 8) {
                left = rect.right - 4;
            }
            var top = rect.top;
            if (top + height > window.innerHeight - 8) {
                top = Math.max(8, window.innerHeight - height - 8);
            }
            fly.style.left = left + 'px';
            fly.style.top = top + 'px';
        }

        document.addEventListener('mouseover', function (event) {
            var item = event.target.closest('.t1-drop__item--parent');
            if (item) {
                t1PlaceFlyout(item);
            }
        });

        document.addEventListener('scroll', function (event) {
            var list = event.target;
            if (!list || !list.classList || !list.classList.contains('t1-drop__list')) {
                return;
            }
            var hovered = list.querySelector('.t1-drop__item--parent:hover');
            if (hovered) {
                t1PlaceFlyout(hovered);
            }
        }, true);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-t1-search]');
        if (!root) {
            return;
        }
        var toggle = root.querySelector('[data-t1-search-toggle]');
        var input = root.querySelector('#input');
        var panel = root.querySelector('#t1-search-panel');
        var openIcon = root.querySelector('[data-t1-search-open-icon]');
        var closeIcon = root.querySelector('[data-t1-search-close-icon]');

        function clearQuery() {
            if (input) {
                input.value = '';
            }
            var form = root.querySelector('#search-vue');
            var vue = form && form.__vue__;
            if (vue) {
                vue.searchInput = '';
                vue.searchedServices = [];
                vue.searchedBlogs = [];
                vue.searchedPortfolios = [];
                vue.searchedProducts = [];
                vue.searchedProductCategories = [];
                vue.searchedBrands = [];
                vue.noResults = false;
                vue.searchLoading = false;
            }
        }

        function setOpen(open) {
            if (!open && root.classList.contains('is-open')) {
                clearQuery();
            }
            root.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute('aria-label', open ? 'بستن جستجو' : 'جستجو');
            if (panel) {
                panel.setAttribute('aria-hidden', open ? 'false' : 'true');
            }
            if (openIcon) {
                openIcon.classList.toggle('d-none', open);
            }
            if (closeIcon) {
                closeIcon.classList.toggle('d-none', !open);
            }
            if (input) {
                input.tabIndex = open ? 0 : -1;
            }
            if (open && input) {
                window.setTimeout(function () {
                    input.focus();
                }, 180);
            } else if (input && document.activeElement === input) {
                input.blur();
            }
        }

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            setOpen(!root.classList.contains('is-open'));
        });

        document.addEventListener('click', function (event) {
            if (!root.contains(event.target)) {
                setOpen(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                setOpen(false);
            }
        });
    });
</script>

<script>
    function height() {
        const parent = document.querySelector("#productsMega").childNodes[1];
        const children = parent.childNodes[1];
        console.log(parent.childNodes)
        const guide = document.getElementById("guide")
        if (children.getBoundingClientRect().height > parent.getBoundingClientRect().height) {
            guide.classList.remove("d-none");
        }
    }

    function heightserve() {
        const parent = document.querySelector("#servicesMega").childNodes[1];
        const children = parent.childNodes[1];
        const guide = document.getElementById("guide")
        if (children.getBoundingClientRect().height > parent.getBoundingClientRect().height) {
            guide.classList.remove("d-none");
        }
    }
</script>

<script>
    const bar1 = document.querySelector('.menu');
    function syncTheme2NavCompact() {
        if (!bar1) return;
        var y = window.pageYOffset || 0;
        bar1.classList.toggle('scrolled', y > 0);
        if (!document.body.classList.contains('theme-theme2')) {
            return;
        }
        var desktop = window.matchMedia('(min-width: 992px)').matches;
        var hidden = bar1.classList.contains('site-header--nav-hidden');
        var shouldHide = desktop && (hidden ? y > 40 : y > 100);
        bar1.classList.toggle('site-header--nav-hidden', shouldHide);
        var navLinks = bar1.querySelector('.site-header__nav');
        if (navLinks) {
            navLinks.setAttribute('aria-hidden', shouldHide ? 'true' : 'false');
            if ('inert' in navLinks) {
                navLinks.inert = shouldHide;
            }
        }
    }
    window.addEventListener('scroll', syncTheme2NavCompact, { passive: true });
    window.addEventListener('resize', syncTheme2NavCompact, { passive: true });
    syncTheme2NavCompact();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var nav = document.querySelector('.site-header__nav');
        if (!nav) return;

        var triggers = nav.querySelectorAll('.site-nav__item[data-mega-panel]');
        var panels = nav.querySelectorAll('.mega-menu__wrap[data-mega-panel]');
        if (!triggers.length || !panels.length) return;

        function syncMegaPanelPosition(panel) {
            var wrap = document.querySelector('.site-header-wrap');
            var header = document.querySelector('nav.site-header');
            var shell = header ? header.querySelector('.site-header__shell') : null;
            if (!header || !panel) return;
            var headerRect = header.getBoundingClientRect();
            var shellRect = shell ? shell.getBoundingClientRect() : headerRect;
            var origin = wrap ? wrap.getBoundingClientRect() : { top: 0, left: 0 };
            panel.style.top = (headerRect.bottom - origin.top) + 'px';
            panel.style.left = (shellRect.left - origin.left) + 'px';
            panel.style.width = shellRect.width + 'px';
            panel.style.right = 'auto';
        }

        function resetMegaPanelPosition(panel) {
            if (!panel) return;
            panel.style.top = '';
            panel.style.left = '';
            panel.style.width = '';
            panel.style.right = '';
        }

        function closeAll() {
            panels.forEach(function (panel) {
                panel.classList.remove('is-open');
                resetMegaPanelPosition(panel);
            });
            document.querySelector('nav.site-header')?.classList.remove('site-header--mega-open');
            document.body.classList.remove('site-mega-open');
        }

        function openPanel(panel) {
            closeAll();
            clearTimeout(closeTimer);
            panel.classList.add('is-open');
            document.querySelector('nav.site-header')?.classList.add('site-header--mega-open');
            document.body.classList.add('site-mega-open');
            syncMegaPanelPosition(panel);
        }

        var closeTimer;
        var lastPageScrollY = window.pageYOffset || 0;

        window.addEventListener('scroll', function () {
            var currentY = window.pageYOffset || 0;
            if (Math.abs(currentY - lastPageScrollY) < 3) {
                return;
            }
            lastPageScrollY = currentY;
            clearTimeout(closeTimer);
            closeAll();
        }, { passive: true });

        window.addEventListener('resize', function () {
            var openPanelEl = nav.querySelector('.mega-menu__wrap.is-open');
            if (openPanelEl) {
                syncMegaPanelPosition(openPanelEl);
            }
        }, { passive: true });

        triggers.forEach(function (trigger) {
            var panelId = trigger.getAttribute('data-mega-panel');
            var panel = nav.querySelector('.mega-menu__wrap[data-mega-panel="' + panelId + '"]');
            if (!panel) return;

            trigger.addEventListener('mouseenter', function () {
                openPanel(panel);
            });

            trigger.addEventListener('mouseleave', function () {
                closeTimer = setTimeout(function () {
                    panel.classList.remove('is-open');
                    resetMegaPanelPosition(panel);
                    document.querySelector('nav.site-header')?.classList.remove('site-header--mega-open');
                    document.body.classList.remove('site-mega-open');
                }, 120);
            });

            panel.addEventListener('mouseenter', function () {
                clearTimeout(closeTimer);
                panel.classList.add('is-open');
                document.querySelector('nav.site-header')?.classList.add('site-header--mega-open');
                document.body.classList.add('site-mega-open');
                syncMegaPanelPosition(panel);
            });

            panel.addEventListener('mouseleave', function () {
                panel.classList.remove('is-open');
                resetMegaPanelPosition(panel);
                document.querySelector('nav.site-header')?.classList.remove('site-header--mega-open');
                document.body.classList.remove('site-mega-open');
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function initHorizontalScroll(wrap, trackSelector, getItems) {
            var track = wrap.querySelector(trackSelector);
            var btnLeft = wrap.querySelector('[class*="__scroll-btn--left"]');
            var btnRight = wrap.querySelector('[class*="__scroll-btn--right"]');
            if (!track || !btnLeft || !btnRight) return;

            function canScrollLeft() {
                var items = getItems();
                if (!items.length) return false;
                var trackRect = track.getBoundingClientRect();
                return items.some(function (item) {
                    return item.getBoundingClientRect().left < trackRect.left - 2;
                });
            }

            function canScrollRight() {
                var items = getItems();
                if (!items.length) return false;
                var trackRect = track.getBoundingClientRect();
                return items.some(function (item) {
                    return item.getBoundingClientRect().right > trackRect.right + 2;
                });
            }

            function updateScrollHints() {
                var hasOverflow = track.scrollWidth - track.clientWidth > 1;
                var showLeft = hasOverflow && canScrollLeft();
                var showRight = hasOverflow && canScrollRight();

                wrap.classList.toggle('has-overflow', hasOverflow);
                wrap.classList.toggle('can-scroll-left', showLeft);
                wrap.classList.toggle('can-scroll-right', showRight);

                btnLeft.classList.toggle('is-visible', showLeft);
                btnRight.classList.toggle('is-visible', showRight);
                btnLeft.disabled = !showLeft;
                btnRight.disabled = !showRight;
            }

            function scrollToward(side) {
                var step = Math.max(track.clientWidth * 0.6, 180);
                var before = track.scrollLeft;
                var delta = side === 'left' ? -step : step;

                track.scrollBy({ left: delta, behavior: 'smooth' });

                window.setTimeout(function () {
                    if (Math.abs(track.scrollLeft - before) < 1) {
                        track.scrollBy({ left: -delta, behavior: 'smooth' });
                    }
                    updateScrollHints();
                }, 280);
            }

            btnLeft.addEventListener('click', function (event) {
                event.preventDefault();
                scrollToward('left');
            });

            btnRight.addEventListener('click', function (event) {
                event.preventDefault();
                scrollToward('right');
            });

            track.addEventListener('scroll', updateScrollHints, { passive: true });
            window.addEventListener('resize', updateScrollHints);
            updateScrollHints();
        }

        document.querySelectorAll('.site-header__nav-scroll').forEach(function (wrap) {
            initHorizontalScroll(wrap, '.site-header__nav-track', function () {
                var list = wrap.querySelector('.site-header__nav-list');
                return list ? Array.prototype.slice.call(list.children) : [];
            });
        });

        document.querySelectorAll('.plp-subcats__scroll').forEach(function (wrap) {
            initHorizontalScroll(wrap, '.plp-subcats__track', function () {
                var track = wrap.querySelector('.plp-subcats__track');
                return track ? Array.prototype.slice.call(track.children) : [];
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var expandSelectors = ['.site-fab'];
        var touchMode = window.matchMedia('(hover: none)').matches;

        if (!touchMode) {
            return;
        }

        expandSelectors.forEach(function(selector) {
            document.querySelectorAll(selector).forEach(function(el) {
                el.addEventListener('click', function(e) {
                    if (!this.classList.contains('is-expanded')) {
                        e.preventDefault();
                        expandSelectors.forEach(function(s) {
                            document.querySelectorAll(s + '.is-expanded').forEach(function(other) {
                                other.classList.remove('is-expanded');
                            });
                        });
                        this.classList.add('is-expanded');
                    }
                });
            });
        });

        document.addEventListener('click', function(e) {
            var inside = expandSelectors.some(function(selector) {
                return e.target.closest(selector);
            });
            if (!inside) {
                expandSelectors.forEach(function(selector) {
                    document.querySelectorAll(selector + '.is-expanded').forEach(function(el) {
                        el.classList.remove('is-expanded');
                    });
                });
            }
        });
    });
</script>
@stack('scripts')
@stack('schema')
@stack('vue')
<script>
    (function () {
        var loader = document.getElementById('site-page-loader');
        var html = document.documentElement;
        var hidden = false;

        function hideSitePageLoader() {
            if (hidden) {
                return;
            }
            hidden = true;
            html.classList.remove('is-site-loading');
            if (document.body) {
                document.body.classList.remove('is-site-loading');
            }
            if (!loader) {
                return;
            }
            loader.classList.add('is-done');
            loader.setAttribute('aria-busy', 'false');
            window.setTimeout(function () {
                if (loader && loader.parentNode) {
                    loader.parentNode.removeChild(loader);
                }
            }, 280);
        }

        window.setTimeout(hideSitePageLoader, 8000);
        if (window.requestAnimationFrame) {
            window.requestAnimationFrame(function () {
                window.requestAnimationFrame(hideSitePageLoader);
            });
        } else {
            window.setTimeout(hideSitePageLoader, 0);
        }
    })();
</script>
