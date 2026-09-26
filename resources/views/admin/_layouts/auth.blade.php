<!doctype html>
<html class="no-js" lang="fa" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#050814">
    <script src="{{ asset('assets/admin/js/admin-theme-boot.js') }}"></script>
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/fav.jpg') }}" type="image/x-icon"/>
    <title>@yield('title', 'پنل مدیریت')</title>
    <meta name="robots" content="noindex, nofollow"/>
    <script src="{{ asset('assets/admin/js/sweetalert2.all.min.js') }}"></script>
    <style>
        @font-face {
            font-family: iransans;
            font-style: normal;
            font-weight: 400;
            src: url("{{ asset('assets/admin/fonts/IRANSansWeb.woff2') }}") format("woff2"),
                 url("{{ asset('assets/admin/fonts/IRANSansWeb.woff') }}") format("woff"),
                 url("{{ asset('assets/admin/fonts/IRANSansWeb.ttf') }}") format("truetype");
            font-display: swap;
        }

        :root {
            --stroke: rgba(255, 255, 255, 0.22);
            --text: #f7fbff;
            --muted: rgba(247, 251, 255, 0.68);
            --accent: #7ae7ff;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
        }

        body {
            font-family: iransans, Tahoma, sans-serif;
            color: var(--admin-text, var(--text));
            background: var(--admin-page-1, #050814);
            overflow-x: hidden;
        }

        .auth-scene {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background:
                radial-gradient(900px 520px at 12% 8%, rgba(91, 124, 255, 0.38), transparent 58%),
                radial-gradient(780px 560px at 92% 18%, rgba(34, 211, 238, 0.22), transparent 52%),
                radial-gradient(820px 640px at 78% 92%, rgba(168, 85, 247, 0.28), transparent 55%),
                radial-gradient(640px 420px at 18% 88%, rgba(14, 165, 233, 0.2), transparent 50%),
                linear-gradient(165deg, #050814 0%, #0a1228 42%, #07101c 100%);
        }

        .auth-bg {
            position: absolute;
            inset: 0;
            z-index: -1;
            pointer-events: none;
        }

        .auth-aurora {
            position: absolute;
            inset: -35%;
            background: conic-gradient(
                from 120deg at 50% 50%,
                rgba(56, 189, 248, 0) 0deg,
                rgba(99, 102, 241, 0.32) 80deg,
                rgba(34, 211, 238, 0.22) 150deg,
                rgba(236, 72, 153, 0.18) 220deg,
                rgba(125, 211, 252, 0.2) 290deg,
                rgba(56, 189, 248, 0) 360deg
            );
            filter: blur(70px);
            opacity: 0.75;
            animation: aurora-spin 48s linear infinite;
        }

        .auth-beam {
            position: absolute;
            top: -30%;
            left: 18%;
            width: 46vw;
            height: 160%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.07), transparent);
            transform: rotate(22deg);
            mix-blend-mode: screen;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(28px);
        }

        .blob-a {
            width: 46vw;
            height: 46vw;
            min-width: 320px;
            min-height: 320px;
            background: radial-gradient(circle, #5b8cff 0%, rgba(91, 140, 255, 0) 70%);
            top: -18vh;
            right: -10vw;
            opacity: 0.9;
            animation: drift 20s ease-in-out infinite;
        }

        .blob-b {
            width: 38vw;
            height: 38vw;
            min-width: 260px;
            min-height: 260px;
            background: radial-gradient(circle, #7c3aed 0%, rgba(124, 58, 237, 0) 72%);
            bottom: -16vh;
            left: -8vw;
            animation: drift 26s ease-in-out infinite reverse;
        }

        .blob-c {
            width: 30vw;
            height: 30vw;
            min-width: 220px;
            min-height: 220px;
            background: radial-gradient(circle, #22d3ee 0%, rgba(34, 211, 238, 0) 70%);
            top: 42%;
            left: 46%;
            opacity: 0.8;
            animation: pulse 14s ease-in-out infinite;
        }

        .blob-d {
            width: 22vw;
            height: 22vw;
            min-width: 160px;
            min-height: 160px;
            background: radial-gradient(circle, #f472b6 0%, rgba(244, 114, 182, 0) 70%);
            top: 18%;
            left: 12%;
            opacity: 0.55;
            animation: drift 16s ease-in-out infinite 1.4s;
        }

        .auth-rings {
            position: absolute;
            width: min(720px, 90vw);
            height: min(720px, 90vw);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid rgba(122, 231, 255, 0.12);
            border-radius: 50%;
            box-shadow:
                0 0 0 70px rgba(122, 231, 255, 0.03),
                0 0 0 140px rgba(139, 123, 255, 0.03);
        }

        .auth-floor {
            position: absolute;
            left: -25%;
            right: -25%;
            bottom: -18%;
            height: 52%;
            background-image:
                linear-gradient(rgba(122, 231, 255, 0.14) 1px, transparent 1px),
                linear-gradient(90deg, rgba(122, 231, 255, 0.14) 1px, transparent 1px);
            background-size: 56px 56px;
            transform: perspective(420px) rotateX(64deg);
            mask-image: linear-gradient(to top, rgba(0, 0, 0, 0.55), transparent 78%);
            -webkit-mask-image: linear-gradient(to top, rgba(0, 0, 0, 0.55), transparent 78%);
        }

        .auth-stars {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1.4px 1.4px at 8% 14%, rgba(255, 255, 255, 0.75), transparent),
                radial-gradient(1.2px 1.2px at 18% 62%, rgba(255, 255, 255, 0.45), transparent),
                radial-gradient(1.6px 1.6px at 27% 22%, rgba(255, 255, 255, 0.65), transparent),
                radial-gradient(1px 1px at 41% 78%, rgba(255, 255, 255, 0.4), transparent),
                radial-gradient(1.5px 1.5px at 58% 12%, rgba(255, 255, 255, 0.7), transparent),
                radial-gradient(1.1px 1.1px at 72% 36%, rgba(255, 255, 255, 0.5), transparent),
                radial-gradient(1.4px 1.4px at 81% 68%, rgba(255, 255, 255, 0.55), transparent),
                radial-gradient(1.2px 1.2px at 91% 18%, rgba(255, 255, 255, 0.6), transparent),
                radial-gradient(1px 1px at 64% 88%, rgba(255, 255, 255, 0.35), transparent);
            opacity: 0.7;
        }

        .auth-vignette {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 42%, rgba(5, 8, 20, 0.72) 100%);
        }

        .auth-noise {
            position: absolute;
            inset: 0;
            opacity: 0.16;
            background-image: radial-gradient(rgba(255, 255, 255, 0.38) 0.55px, transparent 0.55px);
            background-size: 3px 3px;
        }

        .glass-card {
            width: min(440px, 100%);
            padding: 36px 32px 28px;
            border-radius: 28px;
            position: relative;
            z-index: 1;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.16), rgba(255, 255, 255, 0.05));
            border: 1px solid var(--stroke);
            box-shadow:
                0 24px 80px rgba(0, 0, 0, 0.38),
                inset 0 1px 0 rgba(255, 255, 255, 0.28);
            backdrop-filter: blur(28px) saturate(170%);
            -webkit-backdrop-filter: blur(28px) saturate(170%);
        }

        .glass-card::before {
            content: "";
            position: absolute;
            inset: -90px;
            z-index: -1;
            background: radial-gradient(circle, rgba(122, 231, 255, 0.16), transparent 62%);
            filter: blur(12px);
        }

        .auth-kicker {
            margin: 0 0 8px;
            color: var(--accent);
            font-size: 13px;
        }

        .auth-title {
            margin: 0 0 8px;
            font-size: 30px;
            line-height: 1.35;
            font-weight: 700;
        }

        .auth-lead {
            margin: 0 0 28px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .auth-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .auth-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .auth-field label {
            font-size: 13px;
            color: var(--muted);
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-wrap > svg {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--muted);
            pointer-events: none;
        }

        .auth-input {
            width: 100%;
            height: 52px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 16px;
            background: rgba(8, 17, 31, 0.32);
            padding: 0 44px 0 16px;
            font: inherit;
            font-size: 15px;
            color: var(--text);
            outline: none;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: border-color .2s, box-shadow .2s, background .2s;
        }

        .auth-input.has-toggle {
            padding-left: 48px;
        }

        .auth-input::placeholder {
            color: rgba(247, 251, 255, 0.35);
        }

        .auth-input:focus {
            border-color: rgba(122, 231, 255, 0.7);
            background: rgba(8, 17, 31, 0.46);
            box-shadow: 0 0 0 4px rgba(122, 231, 255, 0.14);
        }

        .auth-toggle {
            position: absolute;
            top: 50%;
            left: 8px;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            display: grid;
            place-items: center;
        }

        .auth-toggle:hover,
        .auth-toggle:focus-visible {
            color: var(--text);
            background: rgba(255, 255, 255, 0.08);
        }

        .auth-submit {
            margin-top: 8px;
            height: 52px;
            border: 0;
            border-radius: 16px;
            background: linear-gradient(135deg, #7ae7ff, #8b7bff);
            color: #08111f;
            font: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(122, 231, 255, 0.22);
            transition: transform .15s, filter .2s;
        }

        .auth-submit:hover {
            filter: brightness(1.06);
        }

        .auth-submit:active {
            transform: translateY(1px);
        }

        .auth-footer {
            margin: 20px 0 0;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @keyframes aurora-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(5vw, 4vh) scale(1.08); }
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.7; }
            50% { transform: translate(-46%, -54%) scale(1.14); opacity: 1; }
        }

        @media (max-width: 640px) {
            .glass-card {
                padding: 28px 20px 22px;
                border-radius: 22px;
            }

            .auth-title {
                font-size: 24px;
            }

            .auth-floor,
            .auth-rings {
                opacity: 0.55;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .blob,
            .auth-aurora {
                animation: none;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-glass.css?v1.0') }}">
    @stack('styles')
</head>
<body dir="rtl" class="admin-glass">
@include('admin.components.sweetalert')
<div class="auth-theme-toggle">
    @include('admin._layouts.blocks.theme-toggle')
</div>
<main class="auth-scene">
    <div class="auth-bg" aria-hidden="true">
        <div class="auth-aurora"></div>
        <div class="blob blob-a"></div>
        <div class="blob blob-b"></div>
        <div class="blob blob-c"></div>
        <div class="blob blob-d"></div>
        <div class="auth-rings"></div>
        <div class="auth-beam"></div>
        <div class="auth-floor"></div>
        <div class="auth-stars"></div>
        <div class="auth-vignette"></div>
        <div class="auth-noise"></div>
    </div>
    <section class="glass-card">
        @yield('content')
    </section>
</main>
<script src="{{ asset('assets/admin/js/admin-theme.js') }}"></script>
<script>
    document.querySelectorAll('.auth-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var wrap = btn.closest('.auth-input-wrap');
            var input = wrap ? wrap.querySelector('input') : null;
            if (!input) return;
            var hidden = input.type === 'password';
            input.type = hidden ? 'text' : 'password';
            btn.setAttribute('aria-label', hidden ? 'مخفی کردن رمز عبور' : 'نمایش رمز عبور');
        });
    });
</script>
@stack('scripts')
</body>
</html>
