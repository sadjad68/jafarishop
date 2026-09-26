<script>
    new Vue({
        el: "#app",
        data: {
            highlightBirthday: false,
            selectedYear: initialBirthday.year || '',
            selectedMonth: initialBirthday.month || '',
            selectedDay: initialBirthday.day || '',
            isReadonlyfull_name: true,
            isReadonlymobile: true,
            isDisabledBirthday: true,
            months: monthsData,
            years: (yearsData || []).slice().reverse(),
            daysInMonth: [],
            itemHeight: 44,
            scrollTimers: {},
            isSnapping: false,
        },
        computed: {
            birthdayPreview() {
                if (!this.selectedYear || !this.selectedMonth || !this.selectedDay) {
                    return '';
                }
                var monthKey = this.findMonthKey(this.selectedMonth);
                var monthName = monthKey && this.months[monthKey] ? this.months[monthKey].name : this.selectedMonth;
                return Number(this.selectedDay) + ' ' + monthName + ' ' + this.selectedYear;
            }
        },
        mounted() {
            this.ensureBirthdayDefaults(false);
            this.initializeDaysInMonth();
            this.$nextTick(function () {
                this.measureItemHeight();
                this.syncAllWheels(true);
            }.bind(this));

            if (localStorage.getItem('editBirthday') === '1') {
                this.unlockBirthday();
                localStorage.removeItem('editBirthday');
            }
        },
        methods: {
            measureItemHeight() {
                var wheel = this.getWheelRef('year');
                if (!wheel) return;
                var item = wheel.querySelector('.birthday-roller__item');
                if (item) {
                    this.itemHeight = item.offsetHeight || 44;
                }
            },

            ensureBirthdayDefaults(force) {
                if ((!this.selectedYear || force) && this.years.length) {
                    if (!this.selectedYear) {
                        var mid = Math.min(25, this.years.length - 1);
                        this.selectedYear = String(this.years[mid]);
                    }
                }
                if (!this.selectedMonth) {
                    this.selectedMonth = '01';
                }
                if (!this.selectedDay) {
                    this.selectedDay = '01';
                }
            },

            unlockBirthday() {
                this.isDisabledBirthday = false;
                this.ensureBirthdayDefaults(false);
                this.initializeDaysInMonth();
                this.triggerBlink();
                this.$nextTick(function () {
                    this.measureItemHeight();
                    this.syncAllWheels(true);
                }.bind(this));
            },

            triggerBlink() {
                this.highlightBirthday = true;
                setTimeout(function () {
                    this.highlightBirthday = false;
                }.bind(this), 2500);
            },

            initializeDaysInMonth() {
                var monthKey = this.findMonthKey(this.selectedMonth);
                if (monthKey && this.months[monthKey]) {
                    var maxDays = Number(this.months[monthKey].max);
                    this.daysInMonth = Array.from({ length: maxDays }, function (_, i) { return i + 1; });
                    this.clampDay();
                } else {
                    this.daysInMonth = Array.from({ length: 31 }, function (_, i) { return i + 1; });
                }
            },

            clampDay() {
                if (!this.selectedDay) return;
                var dayNum = Number(this.selectedDay);
                if (dayNum > this.daysInMonth.length) {
                    this.selectedDay = this.daysInMonth.length < 10
                        ? '0' + this.daysInMonth.length
                        : '' + this.daysInMonth.length;
                }
            },

            findMonthKey(val) {
                if (!val) return null;
                var numericVal = Number(val);
                return Object.keys(this.months).find(function (key) {
                    return Number(key) === numericVal;
                });
            },

            getWheelRef(type) {
                if (type === 'year') return this.$refs.yearWheel;
                if (type === 'month') return this.$refs.monthWheel;
                return this.$refs.dayWheel;
            },

            getWheelItems(type) {
                var wheel = this.getWheelRef(type);
                return wheel ? wheel.querySelectorAll('.birthday-roller__item') : [];
            },

            getSelectedValue(type) {
                if (type === 'year') return this.selectedYear;
                if (type === 'month') return this.selectedMonth;
                return this.selectedDay;
            },

            setSelectedValue(type, value) {
                if (type === 'year') {
                    this.selectedYear = String(value);
                    return;
                }
                if (type === 'month') {
                    this.selectedMonth = String(value);
                    this.initializeDaysInMonth();
                    this.$nextTick(function () {
                        this.measureItemHeight();
                        this.scrollToSelected('day', true);
                    }.bind(this));
                    return;
                }
                this.selectedDay = String(value);
            },

            findItemIndex(type, value) {
                var items = this.getWheelItems(type);
                for (var i = 0; i < items.length; i++) {
                    if (String(items[i].getAttribute('data-value')) === String(value)) {
                        return i;
                    }
                }
                return -1;
            },

            getCurrentIndex(type) {
                var wheel = this.getWheelRef(type);
                if (!wheel) return 0;
                var h = this.itemHeight;
                return Math.round(wheel.scrollTop / h);
            },

            scrollToIndex(type, index, instant) {
                var wheel = this.getWheelRef(type);
                var items = this.getWheelItems(type);
                if (!wheel || !items.length) return;

                index = Math.max(0, Math.min(index, items.length - 1));
                var top = index * this.itemHeight;

                this.isSnapping = true;
                if (instant) {
                    wheel.scrollTop = top;
                } else if (typeof wheel.scrollTo === 'function') {
                    wheel.scrollTo({ top: top, behavior: 'smooth' });
                } else {
                    wheel.scrollTop = top;
                }

                var value = items[index].getAttribute('data-value');
                if (String(this.getSelectedValue(type)) !== String(value)) {
                    this.setSelectedValue(type, value);
                }

                setTimeout(function () {
                    this.isSnapping = false;
                }.bind(this), instant ? 0 : 180);
            },

            scrollToSelected(type, instant) {
                var value = this.getSelectedValue(type);
                if (!value && value !== 0) return;
                var index = this.findItemIndex(type, value);
                if (index >= 0) {
                    this.scrollToIndex(type, index, instant);
                }
            },

            syncAllWheels(instant) {
                this.scrollToSelected('year', instant);
                this.scrollToSelected('month', instant);
                this.scrollToSelected('day', instant);
            },

            pickValue(type, value) {
                if (this.isDisabledBirthday) {
                    this.unlockBirthday();
                }
                var index = this.findItemIndex(type, value);
                if (index >= 0) {
                    this.scrollToIndex(type, index, false);
                } else {
                    this.setSelectedValue(type, value);
                }
            },

            onWheelTouch() {
                if (this.isDisabledBirthday) {
                    this.unlockBirthday();
                }
            },

            onWheel(type, event) {
                if (this.isDisabledBirthday) {
                    this.unlockBirthday();
                }

                var direction = event.deltaY > 0 ? 1 : -1;
                var currentIndex = this.getCurrentIndex(type);
                this.scrollToIndex(type, currentIndex + direction, false);
            },

            onWheelScroll(type) {
                if (this.isDisabledBirthday || this.isSnapping) return;

                var self = this;
                if (this.scrollTimers[type]) {
                    clearTimeout(this.scrollTimers[type]);
                }
                this.scrollTimers[type] = setTimeout(function () {
                    self.snapWheel(type);
                }, 120);
            },

            snapWheel(type) {
                var wheel = this.getWheelRef(type);
                if (!wheel) return;

                var index = this.getCurrentIndex(type);
                this.scrollToIndex(type, index, false);
            },

            changeState(refName) {
                if (['year', 'month', 'day'].includes(refName)) {
                    this.unlockBirthday();
                    return;
                }
                var input = this.$refs[refName];
                if (input && input.hasAttribute('readonly')) {
                    input.removeAttribute('readonly');
                    this['isReadonly' + refName] = false;
                }
            }
        },
    });
</script>
