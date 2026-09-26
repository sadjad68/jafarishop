@extends('pages.panel.master')
@section('profile', 'active')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}"
        title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
    @include('pages.panel._partials.page-header', [
        'icon' => 'bi-pencil-square',
        'title' => 'ویرایش اطلاعات',
        'subtitle' => 'نام، شماره تماس و تاریخ تولد خود را به‌روز کنید',
    ])

    <div class="content" id="app">
        <div class="edit-info">
            <div class="login-form">
                <div class="edit-info__head mb-4">
                    <span class="edit-info__head-icon"><i class="bi bi-shield-check"></i></span>
                    <div>
                        <p class="edit-info__head-title m-0">اطلاعات حساب</p>
                        <p class="edit-info__head-meta m-0">برای ویرایش هر فیلد، روی آیکون مداد کلیک کنید</p>
                    </div>
                </div>
                <form action="{{ route('panel.edit-profile') }}" method="POST">
                    @csrf
                    <div class="row w-100 m-0">
                        <div class="col-md-6 col-12 p-1 mb-2">
                            <label for="full_name" class="form-label font-small font-re mb-1">نام و نام خانوادگی</label>
                            <div class="position-relative">
                                <input type="text" class="form-control" name="full_name" ref="full_name" readonly
                                    id="full_name" value="{{ \Illuminate\Support\Facades\Auth::user()->full_name }}">
                                <button type="button" @click="changeState('full_name')"
                                    class="btn btn-edit font-re btn-sm">
                                    <i v-if="isReadonlyfull_name" class="bi bi-pencil d-flex"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 p-1 mb-2">
                            <label for="mobile" class="form-label font-small font-re mb-1">شماره همراه</label>
                            <div class="position-relative">
                                <input onchange="checkMobile(event)" type="text" name="mobile" ref="mobile"
                                    class="form-control text-start" readonly id="mobile"
                                    value="{{ \Illuminate\Support\Facades\Auth::user()->mobile }}">
                                <button type="button" @click="changeState('mobile')"
                                    class="btn btn-edit font-re btn-sm">
                                    <i v-if="isReadonlymobile" class="bi bi-pencil d-flex"></i>
                                </button>
                            </div>
                        </div>
                        @php
                            $date = [];
                            if (isset(\Illuminate\Support\Facades\Auth::user()->birthday)) {
                                $x = \App\Library\NumberHelper::persian2LatinDigit(
                                    jdate(
                                        'Y-m-d',
                                        Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->birthday)->timestamp,
                                    ),
                                );
                                $date = explode('-', $x);
                            }
                        @endphp
                        <div class="col-12 p-1 mb-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label font-small font-re mb-0">تاریخ تولد</label>
                                <button type="button"
                                        class="birthday-roller__edit"
                                        @click="unlockBirthday"
                                        v-if="isDisabledBirthday">
                                    <i class="bi bi-pencil"></i>
                                    ویرایش
                                </button>
                            </div>

                            <input type="hidden" name="year" :value="selectedYear">
                            <input type="hidden" name="month" :value="selectedMonth">
                            <input type="hidden" name="day" :value="selectedDay">

                            <div class="birthday-roller"
                                 :class="{ 'is-locked': isDisabledBirthday, 'is-blink': highlightBirthday }">
                                <div class="birthday-roller__col">
                                    <span class="birthday-roller__label">سال</span>
                                    <div class="birthday-roller__viewport">
                                        <div class="birthday-roller__highlight" aria-hidden="true"></div>
                                        <div class="birthday-roller__wheel"
                                             ref="yearWheel"
                                             @scroll.stop="onWheelScroll('year')"
                                             @wheel.stop.prevent="onWheel('year', $event)"
                                             @touchstart.stop="onWheelTouch"
                                             @mousedown.stop="onWheelTouch">
                                            <div class="birthday-roller__spacer"></div>
                                            <button type="button"
                                                    class="birthday-roller__item font-num-r"
                                                    v-for="year in years"
                                                    :key="'y-' + year"
                                                    :class="{ 'is-active': selectedYear == year }"
                                                    :data-value="year"
                                                    @click.stop="pickValue('year', year)">
                                                @{{ year }}
                                            </button>
                                            <div class="birthday-roller__spacer"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="birthday-roller__col">
                                    <span class="birthday-roller__label">ماه</span>
                                    <div class="birthday-roller__viewport">
                                        <div class="birthday-roller__highlight" aria-hidden="true"></div>
                                        <div class="birthday-roller__wheel"
                                             ref="monthWheel"
                                             @scroll.stop="onWheelScroll('month')"
                                             @wheel.stop.prevent="onWheel('month', $event)"
                                             @touchstart.stop="onWheelTouch"
                                             @mousedown.stop="onWheelTouch">
                                            <div class="birthday-roller__spacer"></div>
                                            <button type="button"
                                                    class="birthday-roller__item"
                                                    v-for="(monthInfo, monthNumber) in months"
                                                    :key="'m-' + monthNumber"
                                                    :class="{ 'is-active': selectedMonth == monthNumber }"
                                                    :data-value="monthNumber"
                                                    @click.stop="pickValue('month', monthNumber)">
                                                @{{ monthInfo.name }}
                                            </button>
                                            <div class="birthday-roller__spacer"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="birthday-roller__col">
                                    <span class="birthday-roller__label">روز</span>
                                    <div class="birthday-roller__viewport">
                                        <div class="birthday-roller__highlight" aria-hidden="true"></div>
                                        <div class="birthday-roller__wheel"
                                             ref="dayWheel"
                                             @scroll.stop="onWheelScroll('day')"
                                             @wheel.stop.prevent="onWheel('day', $event)"
                                             @touchstart.stop="onWheelTouch"
                                             @mousedown.stop="onWheelTouch">
                                            <div class="birthday-roller__spacer"></div>
                                            <button type="button"
                                                    class="birthday-roller__item font-num-r"
                                                    v-for="day in daysInMonth"
                                                    :key="'d-' + day"
                                                    :class="{ 'is-active': Number(selectedDay) === day }"
                                                    :data-value="day < 10 ? '0' + day : '' + day"
                                                    @click.stop="pickValue('day', day < 10 ? '0' + day : '' + day)">
                                                @{{ day }}
                                            </button>
                                            <div class="birthday-roller__spacer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="birthday-roller__preview font-num-r" v-if="birthdayPreview">
                                <i class="bi bi-cake2"></i>
                                @{{ birthdayPreview }}
                            </p>
                        </div>
                    </div>
                    <button type="submit" class="sk-cta mt-3 ms-lg-auto m-auto me-lg-0 py-2 px-4 d-block">ثبت تغییرات</button>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.common.sweetalert')
@endsection
@push('styles')
    <script src="{{ asset('assets/site/js/tpl-sweetalert2.all.min.js') }}"></script>
@endpush
@push('scripts')
    <script src="{{ asset('assets/site/js/panel/tpl-user-panel.js') }}"></script>
    <script src="{{ asset('assets/site/js/tpl-form-validate.js') }}"></script>
    <script>
        var monthsData = @json($months);
        var yearsData = @json($years);
        var initialBirthday = {
            year: @json($date[0] ?? ''),
            month: @json($date[1] ?? ''),
            day: @json($date[2] ?? '')
        };
    </script>
@endpush
@push('vue')
    @include('pages.panel.edit-information._partials.vue')
@endpush
