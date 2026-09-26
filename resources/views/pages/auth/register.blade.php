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
        <div class="auth-shell__body">
            @include('pages.auth._partials.steps', ['activeStep' => 1])

            <div class="auth-shell__layout">
                <div class="auth-shell__form">
                    <div class="auth-card">
                        <div class="auth-card__glow" aria-hidden="true"></div>

                        <div class="auth-mode" role="tablist" aria-label="نوع ورود">
                            <a href="{{ route('auth.index') }}" class="auth-mode__tab">ورود</a>
                            <span class="auth-mode__tab is-active" aria-current="page">ثبت‌نام</span>
                        </div>

                        <div class="auth-panel">
                            <div class="auth-card__head">
                                <span class="auth-card__badge auth-card__badge--violet">حساب جدید</span>
                                <div class="auth-card__icon auth-card__icon--register">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                <h1 class="auth-card__title">ثبت‌نام</h1>
                                <p class="auth-card__meta">اطلاعات خود را وارد کنید تا کد تأیید برایتان ارسال شود.</p>
                            </div>

                            <form action="{{ route('auth.register') }}" method="POST" class="auth-form">
                                @csrf
                                <div class="auth-field">
                                    <label for="register-name" class="auth-field__label">نام و نام خانوادگی</label>
                                    <div class="auth-field__wrap">
                                        <span class="auth-field__icon"><i class="bi bi-person"></i></span>
                                        <input type="text" name="name" class="auth-field__input form-control"
                                               id="register-name" placeholder="علی موحدی" autocomplete="name" required>
                                    </div>
                                </div>
                                <div class="auth-field">
                                    <label for="register-mobile" class="auth-field__label">شماره همراه</label>
                                    <div class="auth-field__wrap">
                                        <span class="auth-field__icon"><i class="bi bi-phone"></i></span>
                                        <input type="tel" name="mobile" class="auth-field__input auth-field__input--ltr form-control font-num-r"
                                               id="register-mobile" placeholder="09123456789" autocomplete="tel" inputmode="numeric" required>
                                    </div>
                                    <p class="auth-field__hint">شماره را با ۰۹ شروع کنید</p>
                                </div>
                                <button type="submit" class="auth-btn w-100 dynamic-color">
                                    <span>ثبت‌نام و دریافت کد</span>
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                            </form>

                            @include('pages.auth._partials.trust')

                            <p class="auth-card__footnote">
                                حساب دارید؟
                                <a href="{{ route('auth.index') }}">ورود به حساب</a>
                            </p>
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
@endpush
