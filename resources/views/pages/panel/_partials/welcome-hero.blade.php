@php
    $hour = (int) App\Library\NumberHelper::persian2LatinDigit(jdate('H')) ;
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'صبح بخیر';
        $emoji = '☀️';
    } elseif ($hour >= 12 && $hour < 17) {
        $greeting = 'ظهر بخیر';
        $emoji = '🌤';
    } elseif ($hour >= 17 && $hour < 21) {
        $greeting = 'عصر بخیر';
        $emoji = '🌅';
    } else {
        $greeting = 'شب بخیر';
        $emoji = '🌙';
    }
    $name = \Illuminate\Support\Facades\Auth::user()->full_name;
@endphp
<div class="panel-hero">
    <div class="panel-hero__glow" aria-hidden="true"></div>
    <div class="panel-hero__content">
        <p class="panel-hero__eyebrow">{{ $emoji }} {{ $greeting }}</p>
        <h2 class="panel-hero__title">{{ $name }} عزیز، خوش آمدید</h2>
        <p class="panel-hero__meta font-num-r">{{ jdate('l j F Y') }}</p>
    </div>
    <div class="panel-hero__actions">
        <a href="{{ route('panel.orders') }}" class="panel-hero__chip">
            <i class="bi bi-handbag"></i>
            سفارشات
        </a>
        <a href="{{ route('basket.cart') }}" class="panel-hero__chip">
            <i class="bi bi-cart3"></i>
            سبد خرید
        </a>
        <a href="{{ route('panel.profile') }}" class="panel-hero__chip panel-hero__chip--ghost">
            <i class="bi bi-person-gear"></i>
            پروفایل
        </a>
    </div>
</div>
