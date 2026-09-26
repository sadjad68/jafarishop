@php
    $isLead = $isLead ?? false;
    $pkgServices = $package->services;
    $pkgShown = $isLead ? $pkgServices->take(5) : $pkgServices->take(3);
    $pkgMore = $pkgServices->count() - $pkgShown->count();
    $pkgExcerpt = $isLead ? trim(strip_tags((string) ($package['description'] ?? ''))) : '';
    $hasDiscount = $package['price'] != 0 && (int) $package['price'] > (int) $package['discounted_price'];
@endphp
<a href="{{ route('package.detail', ['url' => $package['url']]) }}"
   data-reveal
   class="pkg-ticket @if($isLead) pkg-ticket--lead @endif">
    <div class="pkg-ticket__media">
        <img src="{{ $package->getImage() }}"
             alt="{{ $package['title'] }}"
             title="{{ $package['title'] }}"
             width="400"
             height="180"
             loading="{{ $isLead ? 'eager' : 'lazy' }}">
    </div>
    <div class="pkg-ticket__body">
        <p class="pkg-ticket__kicker">{{ $isLead ? 'پکیج منتخب' : 'بسته خدمات' }}</p>
        <p class="pkg-ticket__title">{{ $package['title'] }}</p>
        @if($pkgExcerpt !== '')
            <p class="pkg-ticket__excerpt">{{ \Illuminate\Support\Str::limit($pkgExcerpt, 160) }}</p>
        @endif
        @if($pkgShown->count() > 0)
            <ul class="pkg-ticket__includes">
                @foreach($pkgShown as $package_service)
                    <li>{{ $package_service['title'] }}</li>
                @endforeach
                @if($pkgMore > 0)
                    <li class="pkg-ticket__more">و @toPersianNumber($pkgMore) مورد دیگر</li>
                @endif
            </ul>
        @endif
    </div>
    <span class="pkg-ticket__perf" aria-hidden="true"></span>
    <div class="pkg-ticket__stub">
        @if($hasDiscount)
            <span class="pkg-ticket__off">تخفیف‌دار</span>
        @endif
        <p class="pkg-ticket__amount font-num">
            <span>{{ number_format($package['discounted_price']) }}</span>
            <img src="{{ asset('assets/site/images/toman.svg') }}"
                 class="pkg-ticket__toman"
                 alt=""
                 width="18"
                 height="18"
                 aria-hidden="true">
        </p>
        @if($hasDiscount)
            <p class="pkg-ticket__was font-num-r">
                <del>{{ number_format($package['price']) }}</del>
                <span>تومان</span>
            </p>
        @endif
        <span class="pkg-ticket__cta">
            مشاهده پکیج
        </span>
    </div>
</a>
