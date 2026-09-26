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
    $childCount = isset($services) ? count($services) : 0;
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'service-group-title',
    'bannerEyebrow' => 'خدمات',
    'bannerTitle' => @$service->getH1PagesAttribute($service),
    'bannerCrumbs' => $bannerCrumbs,
    'bannerStatLogo' => $service['image'] ?? null,
    'bannerStatLogoAlt' => $service['title'],
    'bannerStatNum' => $childCount,
    'bannerStatLabel' => 'زیردسته',
])
<div class="container">
    <div class="sk-cta-row mt-3">
        <button type="button"
                class="sk-cta"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal-application">
            <i class="bi bi-pencil-square" aria-hidden="true"></i>
            درخواست خدمات
        </button>
    </div>
</div>
@include('pages.service-detail._partials.service-requesr-form-modal')
@push('schema')
    <script type="application/ld+json">
        {
          "@@context": "https://schema.org/",
          "@@type": "BreadcrumbList",
          "itemListElement": [
            {
              "@@type": "ListItem",
              "position": 1,
              "name": "{{$settings['siteName_fa']}}",
              "item": "{{route('index')}}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "خدمات",
          "item": "{{ route('service.list') }}"
        },
       @if(isset($service->parent_id))
        {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{$service->parent->title}}",
          "item": "{{ route('service.detail', ['url' => $service->parent->url]) }}"
        },
             @endif
           {
          "@@type": "ListItem",
            @if(isset($service->parent_id))
          "position": 4,
          @else
            "position": 3,
@endif
          "name": "{{$service['title']}}",
          "item": "{{ route('service.detail', ['url' => $service->url]) }}"
        }
              ],
      "name": "menu"
    }
    </script>
@endpush
