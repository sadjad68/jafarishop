@extends('admin._layouts.auth')

@section('title', 'تنظیم رمز عبور جدید')

@section('content')
    <p class="auth-kicker">پنل مدیریت</p>
    <h1 class="auth-title">رمز عبور جدید</h1>
    <p class="auth-lead">رمز عبور جدید خود را وارد کنید.</p>
    <form id="cms-form" class="auth-form" action="{{ route('admin.change-password.reset.post') }}" method="POST">
        @csrf
        <div class="auth-field">
            <label for="passwordInput">رمز عبور جدید</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                    <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                </svg>
                <input class="auth-input has-toggle" type="password" name="password" id="passwordInput"
                       autocomplete="new-password" required>
                <button class="auth-toggle" type="button" aria-label="نمایش رمز عبور">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>
        <div class="auth-field">
            <label for="rePasswordInput">تکرار رمز عبور</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                    <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                </svg>
                <input class="auth-input has-toggle" type="password" name="re_password" id="rePasswordInput"
                       autocomplete="new-password" required>
                <button class="auth-toggle" type="button" aria-label="نمایش رمز عبور">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>
        <button type="submit" class="auth-submit">ذخیره رمز عبور</button>
    </form>
    <p class="auth-footer">
        <a href="{{ route('admin.login') }}">بازگشت به صفحه ورود</a>
    </p>
@endsection
