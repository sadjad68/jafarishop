@php
    $activeStep = $activeStep ?? 1;
    $steps = [
        ['key' => 1, 'icon' => 'bi-cart3', 'label' => 'سبد خرید', 'route' => route('basket.cart')],
        ['key' => 2, 'icon' => 'bi-geo-alt-fill', 'label' => 'آدرس و ارسال', 'route' => route('basket.shipping')],
        ['key' => 3, 'icon' => 'bi-credit-card', 'label' => 'تأیید و پرداخت', 'route' => route('basket.payment')],
    ];
@endphp
<nav class="cart-steps" aria-label="مراحل خرید">
    <ol class="cart-steps__list">
        @foreach($steps as $step)
            @php
                $isActive = $step['key'] === $activeStep;
                $isDone = $step['key'] < $activeStep;
                $isClickable = $isDone;
            @endphp
            <li class="cart-steps__item {{ $isActive ? 'is-active' : '' }} {{ $isDone ? 'is-done' : '' }}">
                @if($isClickable)
                    <a href="{{ $step['route'] }}" class="cart-steps__link">
                @else
                    <span class="cart-steps__link" @if($isActive) aria-current="step" @endif>
                @endif
                    <span class="cart-steps__marker">
                        <i class="bi {{ $step['icon'] }}"></i>
                    </span>
                    <span class="cart-steps__label">{{ $step['label'] }}</span>
                @if($isClickable)
                    </a>
                @else
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
