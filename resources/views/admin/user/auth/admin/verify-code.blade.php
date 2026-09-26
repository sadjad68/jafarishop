@extends('admin._layouts.auth')

@section('title', 'تایید کد - بازیابی رمز عبور')

@section('content')
    <p class="auth-kicker">پنل مدیریت</p>
    <h1 class="auth-title">تایید کد</h1>
    <p class="auth-lead">کد ارسال‌شده به شماره {{ $maskedMobile }} را وارد کنید.</p>
    <form id="cms-form" class="auth-form" action="{{ route('admin.change-password.verify.post') }}" method="POST">
        @csrf
        <div class="auth-field">
            <label for="codeInput">کد تایید</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M12 3l8 4v5c0 5-3.4 8.4-8 9.5C7.4 20.4 4 17 4 12V7l8-4Z"></path>
                    <path d="M9 12l2 2 4-4"></path>
                </svg>
                <input class="auth-input" type="text" name="code" id="codeInput"
                       placeholder="123456" value="{{ old('code') }}" required maxlength="6" inputmode="numeric" autocomplete="one-time-code">
            </div>
        </div>
        <button type="submit" class="auth-submit">تایید و ادامه</button>
    </form>
    <p class="auth-footer">
        <a href="{{ route('admin.change-password') }}">تغییر شماره موبایل</a>
    </p>
@endsection
