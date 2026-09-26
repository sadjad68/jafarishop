@if(count($related_samples) > 0)
<section class="sk-related" aria-labelledby="sample-related-title">
    <div class="container">
        <div class="sk-section-head">
            <span class="sk-section-head__eyebrow">گالری</span>
            <h2 id="sample-related-title" class="sk-section-head__title">نمونه کارهای مرتبط</h2>
        </div>
        <div class="sk-sample-grid">
            @foreach($related_samples as $related_sample)
                @if ($related_sample['url'] != null)
                    <a href="{{ route('portfolio.detail', ['url' => $related_sample['url']]) }}"
                       class="sk-sample-card">
                        <span class="sk-sample-card__media">
                            <img src="{{ $related_sample->getImage() }}"
                                 alt="{{ $related_sample['title'] }}"
                                 title="{{ $related_sample['title'] }}"
                                 loading="lazy"
                                 width="480"
                                 height="360">
                        </span>
                        <span class="sk-sample-card__name">
                            {{ $related_sample['title'] }}
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        </span>
                    </a>
                @else
                    <div class="sk-sample-card">
                        <span class="sk-sample-card__media">
                            <img src="{{ $related_sample->getImage() }}"
                                 alt="{{ $related_sample['title'] }}"
                                 title="{{ $related_sample['title'] }}"
                                 loading="lazy"
                                 width="480"
                                 height="360">
                        </span>
                        <span class="sk-sample-card__name">{{ $related_sample['title'] }}</span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
