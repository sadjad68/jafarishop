@php
    $bannerCrumbs = [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'خدمات', 'url' => route('service.list')],
    ];
    if (isset($service->parent_id)) {
        $bannerCrumbs[] = [
            'label' => $service->parent->title,
            'url' => route('service.detail', ['url' => $service->parent->url]),
        ];
    }
    $bannerCrumbs[] = ['label' => $service['title']];
    $sampleCount = isset($samples) ? count($samples) : 0;
    $bannerStats = [
        'bannerTitleId' => 'service-detail-title',
        'bannerEyebrow' => isset($service->parent) ? $service->parent->title : 'خدمات',
        'bannerTitle' => @$service->getH1PagesAttribute($service),
        'bannerCrumbs' => $bannerCrumbs,
    ];
    if ($sampleCount > 0) {
        $bannerStats['bannerStatLogo'] = $service['image'] ?? null;
        $bannerStats['bannerStatLogoAlt'] = $service['title'];
        $bannerStats['bannerStatNum'] = $sampleCount;
        $bannerStats['bannerStatLabel'] = 'نمونه کار';
    }
@endphp
@include('pages._shared.page-banner', $bannerStats)

<div class="container">
    <div class="sk-service-intro">
        <div class="sk-service-intro__copy">
            @if(!empty($service['short_description']))
                <div class="sk-service-intro__lead">
                    {!! $service['short_description'] !!}
                </div>
            @endif
            <div class="sk-cta-row">
                <a href="tel:{{ $service['phone_number'] ? $service['phone_number'] : @$settings['main_phone_number'] }}"
                   id="Header-Call"
                   class="sk-cta">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    تماس با کارشناسان
                </a>
                <button type="button"
                        class="sk-cta sk-cta--ghost"
                        data-bs-toggle="modal"
                        data-bs-target="#exampleModal-application">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    درخواست خدمات
                </button>
            </div>
        </div>
        @if(!empty($service['image']))
            <div class="sk-service-intro__media">
                <img src="{{ $service['image'] }}"
                     alt="{{ $service['title'] }}"
                     title="{{ $service['title'] }}"
                     width="640"
                     height="420">
            </div>
        @endif
    </div>
</div>
@include('pages.service-detail._partials.service-requesr-form-modal')
