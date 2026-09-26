@php
    $pageTitle = @$seo_data->h1 ? $seo_data->h1 : 'خدمات';
    $serviceCount = isset($services) ? count($services) : 0;
@endphp
@include('pages._shared.page-banner', [
    'bannerTitleId' => 'services-page-title',
    'bannerEyebrow' => 'خدمات ما',
    'bannerTitle' => $pageTitle,
    'bannerCrumbs' => [
        ['label' => 'خانه', 'url' => route('index')],
        ['label' => 'خدمات'],
    ],
    'bannerStatIcon' => 'bi-briefcase-fill',
    'bannerStatNum' => $serviceCount,
    'bannerStatLabel' => 'خدمت',
])
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
        }
              ],
      "name": "menu"
    }
    </script>
@endpush
