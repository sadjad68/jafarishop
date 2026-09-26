(function () {
  var timeLimitInMinutes = 2;
  var timeLimitInSeconds = timeLimitInMinutes * 60;
  var totalSeconds = timeLimitInSeconds;
  var timerElement = document.getElementById('timer');
  var btnAgain = document.getElementById('againCode');
  var el = document.getElementById('el');
  var progressRing = document.getElementById('auth-resend-progress');
  var ringCircumference = 97.4;
  var codeLength = 4;
  var isSubmitting = false;

  function persian2digits(str) {
    if (!str) {
      return '';
    }

    var persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
    var arabicNumbers = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];
    var output = String(str);

    for (var i = 0; i < 10; i++) {
      output = output.replace(persianNumbers[i], String(i)).replace(arabicNumbers[i], String(i));
    }

    return output;
  }

  function normalizeDigits(value) {
    return persian2digits(String(value || '')).replace(/\D/g, '');
  }

  function updateRingProgress() {
    if (!progressRing) {
      return;
    }
    var elapsed = totalSeconds - timeLimitInSeconds;
    var progress = elapsed / totalSeconds;
    progressRing.style.strokeDashoffset = String(ringCircumference * (1 - progress));
  }

  function startTimer() {
    timeLimitInSeconds--;
    var minutes = Math.floor(timeLimitInSeconds / 60);
    var seconds = timeLimitInSeconds % 60;

    if (timeLimitInSeconds < 0) {
      if (timerElement) {
        timerElement.textContent = '00:00';
      }
      clearInterval(timerInterval);
      return;
    }

    if (minutes < 10) {
      minutes = '0' + minutes;
    }
    if (seconds < 10) {
      seconds = '0' + seconds;
    }
    if (timerElement) {
      timerElement.textContent = minutes + ':' + seconds;
    }
    updateRingProgress();

    if (minutes == 0 && seconds == 0) {
      if (btnAgain) {
        btnAgain.classList.add('show');
      }
      if (el) {
        el.remove();
      }
    }
  }

  if (timerElement) {
    updateRingProgress();
    var timerInterval = setInterval(startTimer, 1000);
  }

  var otpWrap = document.getElementById('auth-otp');
  if (!otpWrap) {
    return;
  }

  var cells = otpWrap.querySelectorAll('.auth-otp__cell');
  var hiddenInput = document.getElementById('auth-code-hidden');
  var submitBtn = document.getElementById('auth-confirm-submit');
  var confirmForm = document.getElementById('auth-confirm-form');

  function setLoading(loading) {
    otpWrap.classList.toggle('is-loading', loading);
    Array.prototype.forEach.call(cells, function (cell) {
      cell.disabled = loading;
    });
    if (submitBtn) {
      submitBtn.disabled = loading || !hiddenInput || hiddenInput.value.length !== codeLength;
    }
  }

  function clearOtp() {
    Array.prototype.forEach.call(cells, function (cell) {
      cell.value = '';
      cell.classList.remove('is-filled');
    });
    if (hiddenInput) {
      hiddenInput.value = '';
    }
    if (submitBtn) {
      submitBtn.disabled = true;
    }
  }

  function buzzOtp() {
    otpWrap.classList.remove('is-error');
    void otpWrap.offsetWidth;
    otpWrap.classList.add('is-error');
    window.setTimeout(function () {
      otpWrap.classList.remove('is-error');
    }, 520);
  }

  function showCodeError(message) {
    buzzOtp();
    clearOtp();
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        icon: 'error',
        text: message || 'کد نادرست است',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true
      });
    }
    if (cells[0]) {
      cells[0].focus();
    }
  }

  function syncHidden() {
    var code = Array.prototype.map.call(cells, function (cell) {
      return normalizeDigits(cell.value).slice(-1);
    }).join('');

    if (hiddenInput) {
      hiddenInput.value = code;
    }

    Array.prototype.forEach.call(cells, function (cell) {
      cell.classList.toggle('is-filled', cell.value.length > 0);
    });

    if (submitBtn && !isSubmitting) {
      submitBtn.disabled = code.length !== codeLength;
    }

    return code;
  }

  function submitCode() {
    if (isSubmitting || !confirmForm) {
      return;
    }

    var code = syncHidden();
    if (code.length !== codeLength) {
      return;
    }

    if (typeof axios === 'undefined') {
      confirmForm.submit();
      return;
    }

    isSubmitting = true;
    setLoading(true);

    var formData = new FormData(confirmForm);

    axios.post(confirmForm.action, formData, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    }).then(function (response) {
      if (response.data && response.data.redirect) {
        window.location.href = response.data.redirect;
        return;
      }
      isSubmitting = false;
      setLoading(false);
      showCodeError('پاسخ سرور نامعتبر است');
    }).catch(function (error) {
      isSubmitting = false;
      setLoading(false);
      var message = 'کد نادرست است';
      if (error.response && error.response.data && error.response.data.message) {
        message = error.response.data.message;
      }
      showCodeError(message);
    });
  }

  function afterCodeChange() {
    var code = syncHidden();
    if (code.length === codeLength) {
      submitCode();
    }
  }

  function fillFromString(value, focusIndex) {
    var digits = normalizeDigits(value).slice(0, cells.length);

    Array.prototype.forEach.call(cells, function (cell, index) {
      cell.value = digits[index] || '';
    });

    var nextIndex = typeof focusIndex === 'number'
      ? focusIndex
      : Math.min(Math.max(digits.length - 1, 0), cells.length - 1);

    if (digits.length < codeLength && cells[nextIndex]) {
      cells[nextIndex].focus();
      cells[nextIndex].select();
    }

    afterCodeChange();
  }

  Array.prototype.forEach.call(cells, function (cell, index) {
    cell.addEventListener('input', function (event) {
      var value = normalizeDigits(event.target.value);

      if (value.length > 1) {
        fillFromString(value, Math.min(value.length - 1, cells.length - 1));
        return;
      }

      event.target.value = value.slice(-1);

      if (value && cells[index + 1]) {
        cells[index + 1].focus();
      }

      afterCodeChange();
    });

    cell.addEventListener('keydown', function (event) {
      if (event.key === 'Backspace' && !cell.value && cells[index - 1]) {
        cells[index - 1].focus();
        cells[index - 1].value = '';
        syncHidden();
      }

      if (event.key === 'ArrowLeft' && cells[index - 1]) {
        event.preventDefault();
        cells[index - 1].focus();
      }

      if (event.key === 'ArrowRight' && cells[index + 1]) {
        event.preventDefault();
        cells[index + 1].focus();
      }
    });

    cell.addEventListener('paste', function (event) {
      event.preventDefault();
      var pasted = (event.clipboardData || window.clipboardData).getData('text');
      fillFromString(pasted);
    });

    cell.addEventListener('focus', function () {
      cell.select();
    });
  });

  if (confirmForm) {
    confirmForm.addEventListener('submit', function (event) {
      event.preventDefault();
      submitCode();
    });
  }

  syncHidden();

  if (otpWrap.getAttribute('data-has-error') === '1') {
    buzzOtp();
    clearOtp();
  }

  if (cells[0]) {
    cells[0].focus();
  }
})();
