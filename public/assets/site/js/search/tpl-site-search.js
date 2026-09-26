$(window).scroll(function(){
    if ($(this).scrollTop() > 500) {
       $('.tabs-buttons').addClass('fix').addClass('shadow-sm');
    } else {
       $('.tabs-buttons').removeClass('fix').removeClass('shadow-sm');
    }
});

// فرم جستجو - حداقل ۳ کاراکتر
(function () {
    var form  = document.getElementById('search-result-form');
    var input = document.getElementById('search-result-input');
    if (!form || !input) return;

    var errorEl = null;

    function getOrCreateError() {
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.className = 'text-danger small mt-2 me-1';
            errorEl.style.fontFamily = 'inherit';
            input.parentNode.insertAdjacentElement('afterend', errorEl);
        }
        return errorEl;
    }

    form.addEventListener('submit', function (e) {
        var val = input.value.trim();
        if (val.length < 3) {
            e.preventDefault();
            getOrCreateError().textContent = 'لطفاً حداقل ۳ کاراکتر وارد کنید.';
            input.focus();
        } else if (errorEl) {
            errorEl.textContent = '';
        }
    });

    input.addEventListener('input', function () {
        if (errorEl && input.value.trim().length >= 3) {
            errorEl.textContent = '';
        }
    });
})();

// کلیک روی تب – ذخیره تب فعال در URL بدون رفرش
document.querySelectorAll('#pills-tab .nav-link').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var tabId = this.getAttribute('data-bs-target').replace('#', '');
        try {
            var url = new URL(window.location.href);
            url.searchParams.set('tab', tabId);
            history.replaceState(null, '', url.toString());
        } catch (e) {}
        var scrollTarget = document.getElementById('scrollToMe');
        if (scrollTarget) scrollTarget.scrollIntoView({ behavior: 'smooth' });
    });
});