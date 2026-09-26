<span class="visually-hidden">{{ $platform ?? '' }}</span>
<img src="{{ asset('assets/site/images/socials/' . ($platform ?? '') . '.png') }}"
    width="{{ $size ?? 25 }}"
    height="{{ $size ?? 25 }}"
    alt="{{ $platform ?? '' }}"
    class="pdp-social__logo {{ $class ?? '' }}" />
