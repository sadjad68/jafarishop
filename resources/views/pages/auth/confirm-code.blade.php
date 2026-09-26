@extends('layouts.main.master')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
<section class="auth auth-page mx-md-4 mt-md-3 mt-4 mb-5">
    <div class="auth-shell__ambient" aria-hidden="true">
        <span class="auth-shell__orb auth-shell__orb--1"></span>
        <span class="auth-shell__orb auth-shell__orb--2"></span>
    </div>
    <div class="container position-relative">
        <div class="auth-shell__body auth-shell__body--wide">
        @include('pages.auth._partials.steps', ['activeStep' => 2])

        <div class="auth-shell__layout">
            <div class="auth-shell__form">
            <div class="auth-card auth-card--code">
                <div class="auth-card__glow auth-card__glow--green" aria-hidden="true"></div>

                <div class="auth-card__head">
                    <span class="auth-card__badge auth-card__badge--green">تأیید هویت</span>
                    <div class="auth-card__icon auth-card__icon--code">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h1 class="auth-card__title">کد تأیید را وارد کنید</h1>
                    <p class="auth-card__meta">کد یک‌بارمصرف به شماره زیر ارسال شد</p>
                </div>

                <div class="auth-mobile-badge auth-mobile-badge--field">
                    <span class="auth-mobile-badge__icon"><i class="bi bi-phone"></i></span>
                    <span class="auth-mobile-badge__number font-num-r">{{ \request()->get('mobile') }}</span>
                    <a href="{{ route('auth.index') }}" class="auth-mobile-badge__edit">
                        <i class="bi bi-pencil"></i>
                        ویرایش
                    </a>
                </div>

                <ul class="auth-delivery" aria-label="روش‌های ارسال کد">
                    <li class="auth-delivery__chip">
                        <i class="bi bi-chat-dots-fill"></i>
                        <span>پیامک</span>
                    </li>
                    <li class="auth-delivery__chip auth-delivery__chip--bale">
                        <i class="bi bi-send-fill"></i>
                        <span>
                            پیام‌رسان
                            <a target="_blank" rel="noopener" href="https://web.bale.ai/chat">بله</a>
                        </span>
                    </li>
                </ul>

                <form action="{{ route('auth.confirm-code', Request::all()) }}" method="POST" class="auth-form" id="auth-confirm-form">
                    @csrf
                    <input type="hidden" name="mobile" value="{{ \request()->get('mobile') }}">
                    <input type="hidden" name="code" id="auth-code-hidden" value="" required>

                    <div class="auth-field auth-field--otp">
                        <label class="auth-field__label auth-field__label--center">کد ۴ رقمی</label>
                        <div class="auth-otp" id="auth-otp" dir="ltr" @if(session('error')) data-has-error="1" @endif>
                            @for ($i = 0; $i < 4; $i++)
                                <input type="text"
                                       class="auth-otp__cell font-num-r"
                                       inputmode="numeric"
                                       autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                                       maxlength="1"
                                       aria-label="رقم {{ $i + 1 }}">
                            @endfor
                        </div>
                        <p class="auth-field__hint auth-field__hint--center">می‌توانید کد را با اعداد فارسی یا انگلیسی وارد کنید</p>
                    </div>

                    <button type="submit" class="auth-btn w-100 dynamic-color" id="auth-confirm-submit" disabled>
                        <span>ورود به پنل</span>
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </form>

                @include('pages.auth._partials.trust')

                <div class="auth-resend">
                    <div id="el" class="auth-resend__countdown">
                        <div class="auth-resend__ring" aria-hidden="true">
                            <svg viewBox="0 0 36 36">
                                <circle class="auth-resend__ring-track" cx="18" cy="18" r="15.5"></circle>
                                <circle class="auth-resend__ring-progress" id="auth-resend-progress" cx="18" cy="18" r="15.5"></circle>
                            </svg>
                        </div>
                        <div class="auth-resend__timer">
                            <span class="auth-resend__timer-label">ارسال مجدد پس از</span>
                            <span id="timer" class="auth-resend__timer-value font-num-r">02:00</span>
                        </div>
                    </div>
                    <form action="{{ route('auth.login', Request::all()) }}" method="POST" id="auth-form">
                        @csrf
                        <input type="hidden" name="mobile" value="{{ \request()->get('mobile') }}">
                        <button type="submit" id="againCode" class="auth-btn auth-btn--ghost w-100">
                            <i class="bi bi-arrow-repeat"></i>
                            ارسال مجدد کد
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @include('pages.auth._partials.aside')
        </div>
        </div>
    </div>
</section>
@stop

@push('styles')
<link rel="stylesheet" href="{{asset('assets/site/css/auth/tpl-auth-pages.css?v=3.4')}}">
<script src="{{asset('assets/site/js/tpl-sweetalert2.all.min.js')}}"></script>
@endpush
@push('scripts')
<script src="{{asset('assets/site/js/auth/tpl-auth.js?v=0.04')}}"></script>
@endpush
