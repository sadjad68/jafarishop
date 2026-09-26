(function () {
    var KEY = 'theme';

    function normalize(theme) {
        return theme === 'light' ? 'light' : 'dark';
    }

    function current() {
        try {
            return normalize(localStorage.getItem(KEY) || document.documentElement.getAttribute('data-theme'));
        } catch (e) {
            return 'dark';
        }
    }

    function syncChecks(theme) {
        document.querySelectorAll('.theme-switch input[type="checkbox"]').forEach(function (input) {
            input.checked = theme === 'dark';
        });
        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            btn.setAttribute('aria-label', theme === 'dark' ? 'تغییر به تم روشن' : 'تغییر به تم تیره');
            btn.setAttribute('title', theme === 'dark' ? 'تم روشن' : 'تم تیره');
        });
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) {
            meta.setAttribute('content', theme === 'dark' ? '#050814' : '#e8eef8');
        }
    }

    function setTheme(theme) {
        theme = normalize(theme);
        document.documentElement.setAttribute('data-theme', theme);
        document.documentElement.style.colorScheme = theme;
        try {
            localStorage.setItem(KEY, theme);
        } catch (e) {}
        syncChecks(theme);
    }

    window.AdminTheme = {
        get: current,
        set: setTheme,
        toggle: function () {
            setTheme(current() === 'dark' ? 'light' : 'dark');
        }
    };

    setTheme(current());

    function hoistModal(modal) {
        if (modal && modal.classList.contains('modal') && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    }

    document.querySelectorAll('.modal').forEach(hoistModal);

    document.addEventListener('show.bs.modal', function (event) {
        hoistModal(event.target);
    });

    document.addEventListener('click', function (event) {
        var toggle = event.target.closest('[data-theme-toggle]');
        if (!toggle) return;
        event.preventDefault();
        window.AdminTheme.toggle();
    });

    document.addEventListener('change', function (event) {
        if (!event.target.matches('.theme-switch input[type="checkbox"]')) return;
        setTheme(event.target.checked ? 'dark' : 'light');
    });
})();
