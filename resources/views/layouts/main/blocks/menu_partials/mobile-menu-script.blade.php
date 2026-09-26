@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function initMobileMenu(root) {
                if (root.dataset.menuReady) {
                    return;
                }
                root.dataset.menuReady = '1';

                var currentStep = '1';
                var historyStack = [];
                var stepsWrap = root.querySelector('.menu-steps');
                if (stepsWrap) {
                    Array.prototype.slice.call(root.querySelectorAll('.step')).forEach(function (panel) {
                        stepsWrap.appendChild(panel);
                    });
                }
                var steps = Array.prototype.slice.call(root.querySelectorAll('.step'));
                var header = root.querySelector('.menu-header');
                var backBtn = root.querySelector('.back-btn');
                var titleEl = root.querySelector('.menu-title');
                var titleLink = root.querySelector('.menu-title-link');

                function setCrumb(title, url) {
                    if (titleEl) {
                        titleEl.textContent = '';
                        titleEl.appendChild(document.createTextNode('مشاهده '));
                        var span = document.createElement('span');
                        span.textContent = title || '';
                        titleEl.appendChild(span);
                    }
                    if (titleLink) {
                        titleLink.href = url || '#';
                    }
                }

                function currentCrumb() {
                    var span = titleEl ? titleEl.querySelector('span') : null;
                    return {
                        step: currentStep,
                        title: span ? span.textContent : '',
                        url: titleLink ? titleLink.getAttribute('href') : '#'
                    };
                }

                function showStep(stepId, incomingTitle, incomingUrl, isBack) {
                    var targetEl = root.querySelector('.step[data-step="' + stepId + '"]');
                    if (!targetEl || currentStep === stepId) {
                        return;
                    }

                    steps.forEach(function (s) {
                        s.classList.remove('active', 'is-back', 'back');
                    });

                    if (isBack) {
                        targetEl.classList.add('is-back');
                    }

                    requestAnimationFrame(function () {
                        targetEl.classList.add('active');
                        targetEl.classList.remove('is-back', 'back');
                    });

                    if (stepId === '1') {
                        if (header) {
                            header.hidden = true;
                        }
                        if (titleLink) {
                            titleLink.href = '#';
                        }
                    } else {
                        if (header) {
                            header.hidden = false;
                        }
                        if (incomingTitle) {
                            setCrumb(incomingTitle, incomingUrl);
                        }
                    }

                    currentStep = stepId;
                }

                root.addEventListener('click', function (e) {
                    var item = e.target.closest('.js-menu-drill');
                    if (!item || !root.contains(item)) {
                        return;
                    }
                    e.preventDefault();
                    historyStack.push(currentCrumb());
                    showStep(item.getAttribute('data-next'), item.getAttribute('data-title'), item.getAttribute('data-url'), false);
                });

                if (backBtn) {
                    backBtn.addEventListener('click', function () {
                        var prev = historyStack.pop();
                        if (!prev) {
                            return;
                        }
                        showStep(prev.step, prev.title, prev.url, true);
                    });
                }

                var offcanvas = root.closest('.offcanvas');
                if (offcanvas) {
                    offcanvas.addEventListener('hidden.bs.offcanvas', function () {
                        historyStack.length = 0;
                        steps.forEach(function (s) {
                            s.classList.remove('active', 'is-back', 'back');
                        });
                        var first = root.querySelector('.step[data-step="1"]');
                        if (first) {
                            first.classList.add('active');
                        }
                        if (header) {
                            header.hidden = true;
                        }
                        currentStep = '1';
                    });
                }
            }

            document.querySelectorAll('.mobile-menu-container').forEach(initMobileMenu);

            var catDrawer = document.getElementById('offcanvasCat');
            if (catDrawer) {
                var catTriggers = document.querySelectorAll('[data-bs-target="#offcanvasCat"]');
                catDrawer.addEventListener('show.bs.offcanvas', function () {
                    catTriggers.forEach(function (trigger) {
                        trigger.classList.add('is-active');
                    });
                });
                catDrawer.addEventListener('hide.bs.offcanvas', function () {
                    catTriggers.forEach(function (trigger) {
                        trigger.classList.remove('is-active');
                    });
                });
            }
        });
    </script>
@endpush
