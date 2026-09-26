@extends('admin._layouts.auth')

@section('title', 'ورود به پنل مدیریت')

@section('content')
    <p class="auth-kicker">پنل مدیریت</p>
    <h1 class="auth-title">ورود به حساب</h1>
    <p class="auth-lead">ایمیل و رمز عبور را وارد کنید تا به داشبورد دسترسی پیدا کنید.</p>
    <form id="cms-form" class="auth-form" action="{{ url('admin/post-login') }}" method="POST">
        @csrf
        <div class="auth-field">
            <label for="emailInput">آدرس ایمیل</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <path d="M3 7l9 7 9-7"></path>
                </svg>
                <input class="auth-input" type="email" name="email" id="emailInput"
                       placeholder="name@example.com" autocomplete="username" required>
            </div>
        </div>
        <div class="auth-field">
            <label for="passwordInput">رمز عبور</label>
            <div class="auth-input-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                    <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                </svg>
                <input class="auth-input has-toggle" type="password" name="password" id="passwordInput"
                       placeholder="••••••••" autocomplete="current-password" required>
                <button class="auth-toggle" type="button" aria-label="نمایش رمز عبور">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
        </div>
        <button type="submit" id="submitFormCms" class="auth-submit">ورود</button>
    </form>
    <p class="auth-footer">
        برای بازیابی رمز عبور <a href="{{ route('admin.change-password') }}">اینجا</a> کلیک کنید.
    </p>
@endsection
