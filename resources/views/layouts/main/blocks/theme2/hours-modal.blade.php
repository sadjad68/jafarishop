@if (isset($settings['work_hours']))
    @php
        $hoursDays = is_array($settings['work_hours']) ? $settings['work_hours'] : [];
        $hoursTitle = trim((string) (@$settings['work_hours_first_page_title'] ?? ''));
        if ($hoursTitle === '') {
            $hoursTitle = 'ساعت های کاری ما';
        }
        $hoursCta = trim((string) (@$settings['work_hours_first_page_text'] ?? ''));
        $hoursPhones = is_array(@$settings['phone_numbers']) ? $settings['phone_numbers'] : [];

        $normalizeDayName = static function ($name) {
            $name = str_replace(["\u{200C}", '‌', ' '], '', (string) $name);
            return $name;
        };

        $dayOfWeekByName = [
            'شنبه' => 6,
            'یکشنبه' => 0,
            'دوشنبه' => 1,
            'سهشنبه' => 2,
            'چهارشنبه' => 3,
            'پنجشنبه' => 4,
            'جمعه' => 5,
        ];

        $parseMinutes = static function ($value) {
            if ($value === null || $value === '') {
                return null;
            }
            $latin = \App\Library\NumberHelper::persian2LatinDigit(trim((string) $value));
            if (!preg_match('/^(\d{1,2})(?::(\d{1,2}))?$/', $latin, $match)) {
                return null;
            }
            $hour = (int) $match[1];
            $minute = isset($match[2]) ? (int) $match[2] : 0;
            if ($hour > 24 || $minute > 59) {
                return null;
            }
            if ($hour === 24) {
                return 24 * 60;
            }
            return ($hour * 60) + $minute;
        };

        $todayDow = now()->dayOfWeek;
        $nowMinutes = (now()->hour * 60) + now()->minute;
        $todayFrom = null;
        $todayTo = null;
        $todayClosed = false;
        $todayMatched = false;
        $isOpenNow = false;
        $fromMinutes = null;
        $toMinutes = null;

        foreach ($hoursDays as $dayName => $workHour) {
            $mappedDow = $dayOfWeekByName[$normalizeDayName($dayName)] ?? null;
            if ($mappedDow !== $todayDow) {
                continue;
            }
            $todayMatched = true;
            $todayFrom = $workHour['from'] ?? null;
            $todayTo = $workHour['to'] ?? null;
            $todayClosed = ($todayFrom === null || $todayFrom === '') && ($todayTo === null || $todayTo === '');
            $fromMinutes = $parseMinutes($todayFrom);
            $toMinutes = $parseMinutes($todayTo);
            if ($todayClosed || $fromMinutes === null || $toMinutes === null) {
                break;
            }
            if ($fromMinutes === $toMinutes) {
                $isOpenNow = true;
            } elseif ($fromMinutes < $toMinutes) {
                $isOpenNow = $nowMinutes >= $fromMinutes && $nowMinutes < $toMinutes;
            } else {
                $isOpenNow = $nowMinutes >= $fromMinutes || $nowMinutes < $toMinutes;
            }
            break;
        }

        $statusKey = 'unknown';
        $statusText = 'برنامه هفته را ببینید';
        if ($todayMatched && $todayClosed) {
            $statusKey = 'closed';
            $statusText = 'امروز تعطیل هستیم';
        } elseif ($todayMatched && $isOpenNow) {
            $statusKey = 'open';
            $statusText = $todayTo
                ? 'الان باز است · تا ' . \App\Library\NumberHelper::latin2PersianDigit((string) $todayTo)
                : 'الان باز است';
        } elseif ($todayMatched && $fromMinutes !== null && $nowMinutes < $fromMinutes) {
            $statusKey = 'later';
            $statusText = 'امروز از ' . \App\Library\NumberHelper::latin2PersianDigit((string) $todayFrom) . ' باز می‌شویم';
        } elseif ($todayMatched && $todayFrom && $todayTo) {
            $statusKey = 'ended';
            $statusText = 'امروز تا ' . \App\Library\NumberHelper::latin2PersianDigit((string) $todayTo) . ' باز بودیم';
        }
    @endphp
    <div class="modal fade hours-modal"
         id="timeModal"
         tabindex="-1"
         aria-labelledby="hoursModalTitle"
         aria-describedby="hoursModalStatus"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hours-modal__dialog">
            <div class="modal-content hours-modal__content">
                <div class="hours-modal__panel">
                    <span class="hours-modal__handle" aria-hidden="true"></span>
                    <button type="button"
                            class="hours-modal__close"
                            data-bs-dismiss="modal"
                            aria-label="بستن">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>

                    <header class="hours-modal__head">
                        <span class="hours-modal__icon" aria-hidden="true">
                            <i class="bi bi-clock"></i>
                        </span>
                        <div class="hours-modal__intro">
                            <h2 id="hoursModalTitle" class="hours-modal__title">{{ $hoursTitle }}</h2>
                            <p id="hoursModalStatus" class="hours-modal__status hours-modal__status--{{ $statusKey }}">
                                <span class="hours-modal__live" aria-hidden="true"></span>
                                {{ $statusText }}
                            </p>
                        </div>
                    </header>

                    <ul class="hours-modal__days">
                        @foreach ($hoursDays as $dayName => $workHour)
                            @php
                                $isClosedDay = (($workHour['from'] ?? null) === null || $workHour['from'] === '')
                                    && (($workHour['to'] ?? null) === null || $workHour['to'] === '');
                                $isToday = ($dayOfWeekByName[$normalizeDayName($dayName)] ?? null) === $todayDow;
                            @endphp
                            <li class="hours-modal__day{{ $isToday ? ' is-today' : '' }}{{ $isClosedDay ? ' is-closed' : '' }}">
                                <span class="hours-modal__dot" aria-hidden="true"></span>
                                <span class="hours-modal__day-name">
                                    {{ $dayName }}
                                    @if ($isToday)
                                        <span class="hours-modal__today">امروز</span>
                                    @endif
                                </span>
                                @if ($isClosedDay)
                                    <span class="hours-modal__off">تعطیل</span>
                                @else
                                    <span class="hours-modal__range">
                                        @toPersianNumber($workHour['from'])
                                        <span class="hours-modal__until">تا</span>
                                        @toPersianNumber($workHour['to'])
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    @if (count($hoursPhones) > 0)
                        <div class="hours-modal__cta">
                            @if ($hoursCta !== '')
                                <p class="hours-modal__cta-text">{{ $hoursCta }}</p>
                            @endif
                            <ul class="hours-modal__phones">
                                @foreach ($hoursPhones as $phone)
                                    @php
                                        $tel = preg_replace('/[^\d+]/', '', \App\Library\NumberHelper::persian2LatinDigit((string) $phone));
                                    @endphp
                                    @if ($tel !== '')
                                        <li>
                                            <a href="tel:{{ $tel }}" class="hours-modal__phone">
                                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                                <span dir="ltr">@toPersianNumber($phone)</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
