@php
    $compact = $compact ?? false;
    $featured = $featured ?? false;
    $kicker = $kicker ?? null;
    $excerptLimit = $featured ? 240 : ($compact ? 110 : 170);
    $publishRaw = $blog['publish_date'] ?? null;
    $publishTs = $publishRaw
        ? (is_numeric($publishRaw) ? (int) $publishRaw : strtotime((string) $publishRaw))
        : null;
    $publishTs = $publishTs ?: null;
    $readingMinutes = (int) App\Library\SiteHelper::getReadingTime($blog['description']);
    $author = $blog['author'] ?? null;
@endphp
<div class="package-card blog-card{{ $featured ? ' blog-card--featured' : '' }}{{ $compact ? ' blog-card--compact' : '' }}">
    <a href="{{ \App\Library\SiteUrl::blog($blog) }}" class="color-title text-start blog-card__link">
        <div class="blog-card__media">
            <img src="{{ $blog->getItemImage() }}"
                 class="blog-card__image"
                 alt="{{ $blog['title'] }}"
                 title="{{ $blog['title'] }}"
                 width="800"
                 height="560"
                 loading="lazy">
            @if($publishTs)
                <span class="blog-card__stamp" aria-hidden="true">
                    <span class="blog-card__stamp-day">{{ jdate('j', $publishTs) }}</span>
                    <span class="blog-card__stamp-month">{{ jdate('F', $publishTs) }}</span>
                </span>
                <time class="visually-hidden" datetime="{{ date('Y-m-d', $publishTs) }}">
                    {{ jdate('l j F Y', $publishTs) }}
                </time>
            @endif
            @if(!$featured && $kicker)
                <span class="blog-card__kicker">{{ $kicker }}</span>
            @endif
        </div>
        <div class="blog-card__body">
            @if($featured)
                <div class="blog-card__eyebrow">
                    <span class="blog-card__badge">تازه‌ترین</span>
                    @if($kicker)
                        <span class="blog-card__kicker">{{ $kicker }}</span>
                    @endif
                </div>
            @endif
            <h3 class="blog-card__title">{{ $blog['title'] }}</h3>
            <p class="blog-card__excerpt">
                {!! strip_tags(\Illuminate\Support\Str::limit($blog['description'], $excerptLimit)) !!}
            </p>
            <div class="blog-card__meta">
                <span class="blog-card__chip">
                    <i class="bi bi-clock" aria-hidden="true"></i>
                    {{ $readingMinutes }} دقیقه مطالعه
                </span>
                @if(!empty($author))
                    <span class="blog-card__chip">
                        <i class="bi bi-person" aria-hidden="true"></i>
                        {{ $author }}
                    </span>
                @endif
                <span class="blog-card__chip">
                    <i class="bi bi-eye" aria-hidden="true"></i>
                    {{ $blog['view'] }} بازدید
                </span>
            </div>
            <span class="blog-card__cta">
                <span>ادامه مطلب</span>
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
            </span>
        </div>
    </a>
</div>
