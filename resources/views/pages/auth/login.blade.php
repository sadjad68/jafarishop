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
                <div id="app" class="auth-shell__form">
                    <form action="{{ route('auth.login', Request::all()) }}" method="POST" id="auth-form"
                          @submit.prevent="disableSubmit">
                        @csrf

                        <div class="auth-card">
                            <div class="auth-card__glow" aria-hidden="true"></div>

                            <div class="auth-mode" role="tablist" aria-label="ورود یا ثبت‌نام">
                                <button type="button" class="auth-mode__tab"
                                        role="tab"
                                        :aria-selected="userExists ? 'true' : 'false'"
                                        :class="{ 'is-active': userExists }"
                                        @click="backToLogin">
                                    ورود
                                </button>
                                <button type="button" class="auth-mode__tab"
                                        role="tab"
                                        :aria-selected="userExists ? 'false' : 'true'"
                                        :class="{ 'is-active': !userExists }"
                                        @click="showRegister">
                                    ثبت‌نام
                                </button>
                            </div>

                            <div class="auth-switch">
                                <transition name="auth-flip" mode="out-in">
                                    <div v-if="userExists" key="login" class="auth-panel">
                                        <div class="auth-card__head">
                                            <span class="auth-card__badge">ورود با موبایل</span>
                                            <div class="auth-card__icon">
                                                <i class="bi bi-person-circle"></i>
                                            </div>
                                            <h1 class="auth-card__title">ورود به حساب</h1>
                                            <p class="auth-card__meta">شماره موبایل خود را وارد کنید تا کد تأیید برایتان ارسال شود.</p>
                                        </div>

                                        <div class="auth-form">
                                            <div class="auth-field">
                                                <label for="auth-mobile" class="auth-field__label">شماره همراه</label>
                                                <div class="auth-field__wrap">
                                                    <span class="auth-field__icon"><i class="bi bi-phone"></i></span>
                                                    <input v-model="mobile" name="mobile" type="tel"
                                                           class="auth-field__input auth-field__input--ltr form-control font-num-r"
                                                           id="auth-mobile" placeholder="09123456789"
                                                           required oninvalid="warnRequired(' شماره همراه')"
                                                           onchange="checkMobile(event)" autocomplete="tel" inputmode="numeric">
                                                </div>
                                                <p class="auth-field__hint">شماره را با ۰۹ شروع کنید</p>
                                            </div>

                                            <button type="button" @click="checkUserExists"
                                                    class="auth-btn w-100 dynamic-color" :disabled="loading">
                                                <span>دریافت کد تأیید</span>
                                                <i class="bi bi-arrow-left"></i>
                                                <div v-if="loading" class="spinner-border spinner-border-sm" role="status"></div>
                                            </button>
                                        </div>

                                        @include('pages.auth._partials.trust')

                                        <p class="auth-card__footnote">
                                            با ادامه، <a href="{{ route('index') }}">قوانین و مقررات</a> سایت را می‌پذیرید.
                                        </p>
                                    </div>

                                    <div v-else key="register" class="auth-panel">
                                        <div class="auth-card__head">
                                            <span class="auth-card__badge auth-card__badge--violet">حساب جدید</span>
                                            <div class="auth-card__icon auth-card__icon--register">
                                                <i class="bi bi-person-plus"></i>
                                            </div>
                                            <h1 class="auth-card__title">ثبت‌نام</h1>
                                            <p class="auth-card__meta">فقط یک قدم تا ساخت حساب — نام و شماره موبایل کافی است.</p>
                                        </div>

                                        <div class="auth-form">
                                            <div class="auth-field">
                                                <label for="auth-name" class="auth-field__label">نام و نام خانوادگی</label>
                                                <div class="auth-field__wrap">
                                                    <span class="auth-field__icon"><i class="bi bi-person"></i></span>
                                                    <input required oninvalid="warnRequired(' نام ونام خانوادگی')" name="name"
                                                           type="text" class="auth-field__input form-control" id="auth-name"
                                                           placeholder="علی موحدی" autocomplete="name" ref="nameInput">
                                                </div>
                                            </div>

                                            <div class="auth-field">
                                                <label for="auth-mobile-reg" class="auth-field__label">شماره همراه</label>
                                                <div class="auth-field__wrap">
                                                    <span class="auth-field__icon"><i class="bi bi-phone"></i></span>
                                                    <input v-model="mobile" name="mobile" type="tel"
                                                           class="auth-field__input auth-field__input--ltr form-control font-num-r"
                                                           id="auth-mobile-reg" placeholder="09123456789"
                                                           required oninvalid="warnRequired(' شماره همراه')"
                                                           onchange="checkMobile(event)" autocomplete="tel" inputmode="numeric">
                                                </div>
                                                <p class="auth-field__hint">شماره را با ۰۹ شروع کنید</p>
                                            </div>

                                            <button type="submit" class="auth-btn w-100 dynamic-color" id="submit-register" :disabled="loading">
                                                <span>ثبت‌نام و دریافت کد</span>
                                                <i class="bi bi-arrow-left"></i>
                                                <div v-if="loading" class="spinner-border spinner-border-sm" role="status"></div>
                                            </button>

                                            <button type="button" class="auth-btn auth-btn--ghost auth-btn--sm w-100" @click="backToLogin">
                                                <i class="bi bi-arrow-right"></i>
                                                بازگشت به ورود
                                            </button>
                                        </div>

                                        @include('pages.auth._partials.trust')

                                        <p class="auth-card__footnote">
                                            با ثبت‌نام، <a href="{{ route('index') }}">قوانین و مقررات</a> سایت را می‌پذیرید.
                                        </p>
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </form>
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
    <script src="{{asset('assets/site/js/tpl-form-validate.js')}}"></script>
@endpush
@push('vue')
    @include('pages.auth._partials.vue')
@endpush
