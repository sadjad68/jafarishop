<script>
    (function() {
        function toFaDigits(n) {
            return String(n).padStart(2, "0").replace(/[0-9]/g, function (d) {
                return "۰۱۲۳۴۵۶۷۸۹".charAt(d);
            });
        }

        @foreach($timer_products as $timer)
        (function() {
            const second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24,
                countDown = new Date("{{ $timer['end_timer'] }}").getTime();
            let timerId;

            function tick() {
                const daysEl = document.getElementById("days{{ $timer['id'] }}");
                const hoursEl = document.getElementById("hours{{ $timer['id'] }}");
                const minutesEl = document.getElementById("minutes{{ $timer['id'] }}");
                const secondsEl = document.getElementById("seconds{{ $timer['id'] }}");

                if (!daysEl || !hoursEl || !minutesEl || !secondsEl) {
                    clearInterval(timerId);
                    return;
                }

                let distance = countDown - Date.now();
                if (distance < 0) {
                    distance = 0;
                    clearInterval(timerId);
                }

                daysEl.innerText = toFaDigits(Math.floor(distance / day));
                hoursEl.innerText = toFaDigits(Math.floor((distance % day) / hour));
                minutesEl.innerText = toFaDigits(Math.floor((distance % hour) / minute));
                secondsEl.innerText = toFaDigits(Math.floor((distance % minute) / second));
            }

            tick();
            timerId = setInterval(tick, 1000);
        }());
        @endforeach
    }());
</script>
