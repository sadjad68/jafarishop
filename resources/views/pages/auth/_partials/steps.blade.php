@php
    $activeStep = $activeStep ?? 1;
    $steps = [
        ['key' => 1, 'icon' => 'bi-phone', 'label' => 'شماره موبایل'],
        ['key' => 2, 'icon' => 'bi-shield-lock', 'label' => 'کد تأیید'],
    ];
@endphp
<nav class="auth-steps" aria-label="مراحل ورود">
    <ol class="auth-steps__list">
        @foreach($steps as $step)
            @php
                $isActive = $step['key'] === $activeStep;
                $isDone = $step['key'] < $activeStep;
            @endphp
            <li class="auth-steps__item {{ $isActive ? 'is-active' : '' }} {{ $isDone ? 'is-done' : '' }}">
                <span class="auth-steps__link" @if($isActive) aria-current="step" @endif>
                    <span class="auth-steps__marker">
                        @if($isDone)
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi {{ $step['icon'] }}"></i>
                        @endif
                    </span>
                    <span class="auth-steps__label">{{ $step['label'] }}</span>
                </span>
            </li>
        @endforeach
    </ol>
</nav>
