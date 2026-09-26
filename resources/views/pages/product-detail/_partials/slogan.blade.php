<div class="pdp-slogans">
    <div class="pdp-slogans__grid">
        @foreach ($slogans as $slogan)
            <div class="pdp-slogans__item">
                <div class="pdp-slogans__icon">
                    <img src="{{ $slogan->image }}" width="24" height="24" alt="{{ $slogan['value'] }}" title="{{ $slogan['value'] }}" loading="lazy" />
                </div>
                <p class="pdp-slogans__text">{{ $slogan['value'] }}</p>
            </div>
        @endforeach
    </div>
</div>
