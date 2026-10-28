@php
    $adsText = trim((string) (@$settings['ads_text'] ?? ''));
    if ($adsText === '') {
        $adsText = 'تماس جهت مشاوره';
    }
    $adsNumber = trim((string) (@$settings['ads_number'] ?? ''));
    $adsTel = preg_replace('/[^\d+]/', '', $adsNumber) ?? '';
@endphp
<div class="call-fixed">
    @if ($adsTel !== '')
        <a class="call-fixed__link" href="tel:{{ $adsTel }}" aria-label="{{ $adsText }}، {{ $adsNumber }}">
    @else
        <div class="call-fixed__link">
    @endif
        <img class="call-fixed__icon"
             src="{{ asset('assets/site/images/telephone.png') }}"
             width="22"
             height="22"
             alt="">
        <span class="call-fixed__text">{{ $adsText }}</span>
        @if ($adsNumber !== '')
            <span class="call-fixed__number" dir="ltr">@toPersianNumber($adsNumber)</span>
        @endif
    @if ($adsTel !== '')
        </a>
    @else
        </div>
    @endif
</div>
