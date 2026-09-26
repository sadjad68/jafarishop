<ul class="pdp-slogans-list list-unstyled m-0">
    @foreach ($slogans as $slogan)
        <li class="pdp-slogans-list__item">
            <span class="pdp-slogans-list__icon">
                <img src="{{ $slogan->image }}" width="22" height="22" alt="{{ $slogan['value'] }}" title="{{ $slogan['value'] }}" loading="lazy" />
            </span>
            <span class="pdp-slogans-list__text">{{ $slogan['value'] }}</span>
        </li>
    @endforeach
</ul>
