@extends('layouts.main.master')
@section('robots', @$product->seoIndex == 0 ? 'index,follow' : 'noindex,nofollow')
@section('title_seo', @$product->seoTitle ? @$product->seoTitle : @$product->title)
@section('description_seo', @$product->seoDescription)
@section('image_seo', @$product->getImage('big'))
@push('meta_tags')
    <meta name="product_id" content="{{ $product->id }}" />
    <meta name="product_name" content="{{ $product->title }}" />
    <meta name="product_price"
        content="{{ intval($product['discounted_price']) != 0 ? intval($product['discounted_price']) : intval($product['price']) }}" />
    <meta name="product_old_price"
        content="{{ intval($product['discounted_price']) != 0 ? intval($product['price']) : '' }}" />
    <meta name="availability" content="{{ intval($product->stock) != 0 ? 'instock' : 'outofstock' }}" />
@endpush
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}"
        title="{{ $settings['siteName_fa'] }}" class="logo-menu" />
@endsection
@section('type', 'product')
@section('content')
    @if ($product->mainVariant && count($product->variants) > 0)
        <table class="d-none">
            <tbody>
                @foreach ($product->variants as $variant)
                    <tr>
                        <td class="name">{{ $product->mainVariant->title }}</td>
                        <th class="value">
                            {{ @$variant->specification->title }}
                            {{ @$variant->stock == 0 ? 'outofstock' : 'instock' }}
                            {{ @$variant->final_price == 0 ? '- ناموجود' : @$variant->final_price }}
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="pdp-page product-page">
        @include('pages.product-detail._partials.breadcrumb')

        <section class="pdp-main" aria-label="جزئیات محصول">
            <div class="container">
                <div class="pdp-sheet" id="app" v-cloak>
                    <div class="pdp-sheet__visual" id="targetSection">
                        @include('pages.product-detail._partials.product-image')
                    </div>

                    <div class="pdp-sheet__counter">
                        @include('pages.product-detail._partials.info-product')
                    </div>

                    @include('pages.product-detail._partials.add-to-cart-sticky')
                </div>

                @if (count($properties) != 0 || count($variants) != 0)
                    @include('pages.product-detail._partials.slogan')
                @endif

                @include('pages.product-detail._partials.tabs')
            </div>
        </section>

        @include('pages.product-detail._partials.related')
        @include('pages.product-detail._partials.complement')

        @if (
            @$settings['show_share_button'] == 1 &&
                !empty(@$settings['share_type']))
            @if (in_array(@$settings['share_button_display_type'], ['fixed', 'both']))
                <div class="pdp-social-fixed d-none d-lg-block">
                    @include('pages.product-detail._partials.components.btn-pm-social')
                </div>
            @endif
        @endif

        @include('pages.product-detail._partials.notification-modal')
    </div>
@stop

@push('styles')
    @include('pages.product-detail._partials.styles')
@endpush
@push('scripts')
    @include('pages.product-detail._partials.scripts')
@endpush
@push('schema')
    @include('pages.product-detail._partials.schema')
@endpush
@push('vue')
    @include('pages.product-detail._partials.vue')
@endpush
