(function () {
    'use strict';

    function getInput(zone) {
        return zone.querySelector('.admin-dropzone-input');
    }

    function matchesAccept(file, accept) {
        if (!accept) {
            return true;
        }

        return accept.split(',').some(function (part) {
            part = part.trim();
            if (!part) {
                return false;
            }
            if (part.endsWith('/*')) {
                return file.type.indexOf(part.slice(0, -1)) === 0;
            }
            if (part.charAt(0) === '.') {
                return file.name.toLowerCase().endsWith(part.toLowerCase());
            }
            return file.type === part;
        });
    }

    function assignFiles(input, fileList) {
        var accept = input.getAttribute('accept');
        var files = Array.prototype.slice.call(fileList).filter(function (file) {
            return matchesAccept(file, accept);
        });

        if (!files.length) {
            return false;
        }

        if (!input.multiple) {
            files = files.slice(0, 1);
        }

        var dt = new DataTransfer();
        files.forEach(function (file) {
            dt.items.add(file);
        });

        input.files = dt.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
        return true;
    }

    function isEnteringZone(event, zone) {
        return !event.relatedTarget || !zone.contains(event.relatedTarget);
    }

    function isLeavingZone(event, zone) {
        return !event.relatedTarget || !zone.contains(event.relatedTarget);
    }

    document.addEventListener('dragenter', function (event) {
        var zone = event.target.closest('.admin-dropzone');
        if (!zone || !isEnteringZone(event, zone)) {
            return;
        }

        event.preventDefault();
        zone.classList.add('is-dragover');
    });

    document.addEventListener('dragover', function (event) {
        var zone = event.target.closest('.admin-dropzone');
        if (!zone) {
            return;
        }

        event.preventDefault();
        if (event.dataTransfer) {
            event.dataTransfer.dropEffect = 'copy';
        }
    });

    document.addEventListener('dragleave', function (event) {
        var zone = event.target.closest('.admin-dropzone');
        if (!zone || !isLeavingZone(event, zone)) {
            return;
        }

        zone.classList.remove('is-dragover');
    });

    document.addEventListener('drop', function (event) {
        var zone = event.target.closest('.admin-dropzone');
        if (!zone) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        zone.classList.remove('is-dragover');

        var input = getInput(zone);
        if (!input || input.disabled) {
            return;
        }

        var files = event.dataTransfer && event.dataTransfer.files;
        if (!files || !files.length) {
            return;
        }

        assignFiles(input, files);
    });
})();
