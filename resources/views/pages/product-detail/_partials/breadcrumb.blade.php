@php
    $pdpCrumbs = [
        ['label' => 'خانه', 'url' => route('index')],
    ];
    $primaryCategory = isset($categories) ? $categories->first() : null;
    if ($primaryCategory) {
        foreach ($primaryCategory->getAllParents() as $parent) {
            $pdpCrumbs[] = [
                'label' => $parent['title'],
                'url' => \App\Library\SiteUrl::category($parent),
            ];
        }
        $pdpCrumbs[] = [
            'label' => $primaryCategory['title'],
            'url' => \App\Library\SiteUrl::category($primaryCategory),
        ];
    } else {
        $pdpCrumbs[] = [
            'label' => 'همه محصولات',
            'url' => route('product.get-all'),
        ];
    }
    $pdpCrumbs[] = ['label' => $product['title']];

    $bannerArgs = [
        'bannerTitleId' => 'product-detail-title',
        'bannerEyebrow' => 'مسیر خرید',
        'bannerTitle' => @$product->getH1PagesAttribute($product),
        'bannerCrumbs' => $pdpCrumbs,
        'bannerMetaView' => 'pages.product-detail._partials.rate',
    ];

    if ($brand) {
        $bannerArgs['bannerBrandTitle'] = @$brand['title'];
        $bannerArgs['bannerBrandUrl'] = \App\Library\SiteUrl::brand($brand);
        $bannerArgs['bannerBrandLogo'] = $brand->item_image;
    }
@endphp
@include('pages._shared.page-banner', $bannerArgs)

@if (@$settings['products_alert'])
    <div class="container">
        <div class="pdp-header__alert title-warning d-flex align-items-center gap-1">
            <div class="blink-circle">
                <i class="bi bi-circle-fill d-flex" aria-hidden="true"></i>
            </div>
            <p class="m-0">{!! $settings['products_alert'] !!}</p>
        </div>
    </div>
@endif
