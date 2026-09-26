@php
    $bannerTitleId = $bannerTitleId ?? 'page-banner-title';
    $bannerCrumbs = $bannerCrumbs ?? [];
    $bannerHasStat = ($bannerShowStat ?? true)
        && (
            !empty($bannerStatLogo)
            || !empty($bannerStatIcon)
            || isset($bannerStatNum)
            || !empty($bannerStatLabel)
        );
@endphp
<header class="plp-header">
    <div class="container">
        <div class="sk-page-banner{{ $bannerHasStat ? '' : ' sk-page-banner--solo' }}">
            <div class="sk-page-banner__copy">
                @if(!empty($bannerBrandUrl) && !empty($bannerBrandTitle))
                    <a href="{{ $bannerBrandUrl }}" class="sk-page-banner__brand">
                        @if(!empty($bannerBrandLogo))
                            <img src="{{ $bannerBrandLogo }}" alt="" width="28" height="28" loading="lazy" />
                        @endif
                        <span>{{ $bannerBrandTitle }}</span>
                    </a>
                @elseif(!empty($bannerEyebrow))
                    <span class="sk-page-banner__eyebrow">{{ $bannerEyebrow }}</span>
                @endif
                <h1 id="{{ $bannerTitleId }}" class="sk-page-banner__title">
                    {{ $bannerTitle }}
                </h1>
                @if(!empty($bannerMetaView))
                    <div class="sk-page-banner__meta">
                        @include($bannerMetaView)
                    </div>
                @endif
                <nav class="sk-page-banner__crumb" aria-label="breadcrumb">
                    @foreach($bannerCrumbs as $index => $crumb)
                        @if($index > 0)
                            <span class="sk-page-banner__sep" aria-hidden="true">/</span>
                        @endif
                        @if(!empty($crumb['url']))
                            <a href="{{ $crumb['url'] }}">
                                @if($index === 0)
                                    <i class="bi bi-house" aria-hidden="true"></i>
                                @endif
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span aria-current="page">{{ $crumb['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            </div>
            @if($bannerHasStat)
                <div class="sk-page-banner__stat">
                    @if(!empty($bannerStatLogo))
                        <img src="{{ $bannerStatLogo }}"
                             alt="{{ $bannerStatLogoAlt ?? '' }}"
                             class="sk-page-banner__stat-logo"
                             width="52"
                             height="52">
                    @elseif(!empty($bannerStatIcon))
                        <i class="bi {{ $bannerStatIcon }} sk-page-banner__stat-icon" aria-hidden="true"></i>
                    @endif
                    @isset($bannerStatNum)
                        <strong class="sk-page-banner__stat-num">{{ $bannerStatNum }}</strong>
                    @endisset
                    @if(!empty($bannerStatLabel))
                        <span class="sk-page-banner__stat-label">{{ $bannerStatLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</header>
