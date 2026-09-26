@extends('admin._layouts.auth')

@section('title', 'بازیابی رمز عبور پنل مدیریت')

@section('content')
    <p class="auth-kicker">پنل مدیریت</p>
    <h1 class="auth-title">بازیابی رمز عبور</h1>
    <p class="auth-lead">شماره موبایل ثبت‌شده در حساب ادمین را وارد کنید.</p>
    <form id="cms-form" class="auth-form" action="{{ route('admin.change-password.send-code') }}" method="POST">
        @csrf
        <div class="auth-field">
            <label for="mobileInput">شماره موبایل</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="7" y="2" width="10" height="20" rx="2"></rect>
                    <path d="M11 18h2"></path>
                </svg>
                <input class="auth-input" type="text" name="mobile" id="mobileInput"
                       placeholder="09123456789" value="{{ old('mobile') }}" autocomplete="tel" required>
            </div>
        </div>
        <button type="submit" id="submitFormCms" class="auth-submit">ارسال کد تایید</button>
    </form>
    <p class="auth-footer">
        <a href="{{ route('admin.login') }}">بازگشت به صفحه ورود</a>
    </p>
@endsection
