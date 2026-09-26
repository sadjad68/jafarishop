@php
    $hasDiscount = $package['price'] != 0 && (int) $package['price'] > (int) $package['discounted_price'];
    $pkgDock = $pkgDock ?? false;
    $pkgPhone = trim((string) (@$settings['main_phone_number'] ?? ''));
@endphp
<div class="pkg-stub @if($pkgDock) pkg-stub--dock @else pkg-stub--panel @endif">
    @if($hasDiscount)
        <span class="pkg-stub__off">تخفیف‌دار</span>
    @endif
    <p class="pkg-stub__label">مبلغ پکیج</p>
    <p class="pkg-stub__amount font-num">
        <span>{{ number_format($package['discounted_price']) }}</span>
        <img src="{{ asset('assets/site/images/toman.svg') }}"
             class="pkg-stub__toman"
             alt=""
             width="20"
             height="20"
             aria-hidden="true">
    </p>
    @if($hasDiscount)
        <p class="pkg-stub__was font-num-r">
            <del>{{ number_format($package['price']) }}</del>
            <span>تومان</span>
        </p>
    @endif
    @if($pkgPhone !== '')
        <a href="tel:{{ $pkgPhone }}" id="{{ $pkgDock ? 'Dock-Call' : 'Header-Call' }}" class="t1-btn t1-btn--solid pkg-stub__cta">
            <i class="bi bi-telephone" aria-hidden="true"></i>
            تماس برای رزرو
        </a>
    @endif
</div>
