<a href="{{ route('service.detail', ['url' => $service['url']]) }}" class="sk-service-card">
    <span class="sk-service-card__media">
        <img src="{{ $service['image'] }}"
             alt="{{ $service['title'] }}"
             title="{{ $service['title'] }}"
             loading="lazy"
             width="480"
             height="360">
    </span>
    <span class="sk-service-card__name">
        {{ $service['title'] }}
        <i class="bi bi-chevron-left" aria-hidden="true"></i>
    </span>
</a>
