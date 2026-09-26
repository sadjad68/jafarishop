@extends('layouts.main.master')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('title_seo')سبد خرید@endsection
@section('description_seo')سبد خرید@endsection
@section('content')
    <section class="cart cart-page mx-md-4 mt-md-3 mt-4 mb-5">
        <div class="container" id="app" v-cloak>
            <div v-if="itemListLoading == true">
                <div class="cart-card cart-card--loading">
                    @include('layouts.common.loading')
                </div>
            </div>
            <div v-else>
                <div v-if="items.length != 0">
                    @include('pages.cart._partials.steps', ['activeStep' => 1])

                    @if(@$settings['basket_description'])
                        <div class="cart-notice" role="alert">
                            <i class="bi bi-info-circle"></i>
                            <span>{{ @$settings['basket_description'] }}</span>
                        </div>
                    @endif

                    <div class="checkout row w-100 m-0">
                        <div class="col-xl-9 col-lg-8 ps-0 pe-0 pe-lg-2 mt-4">
                            <div class="cart-card cart-card--products">
                                <div class="cart-card__head">
                                    <div>
                                        <h1 class="cart-card__title">سبد خرید شما</h1>
                                        <p class="cart-card__meta font-num-r">@{{ totalQuantity }} کالا</p>
                                    </div>
                                    <a href="{{ route('basket.cart-delete') }}"
                                       class="cart-link-danger">
                                        <i class="bi bi-trash3"></i>
                                        حذف همه
                                    </a>
                                </div>

                                <div class="cart-products">
                                    <article class="cart-product"
                                             v-for="(item, index) in items" :key="item.id">
                                        @include('pages.cart._partials.checkout.item-list')
                                    </article>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4 pe-0 ps-0 ps-lg-2 mt-4">
                            @include('pages.cart._partials.checkout.price-box')
                        </div>
                    </div>

                    <div class="cart-mobile-bar d-lg-none">
                        <div class="cart-mobile-bar__price">
                            <span class="cart-mobile-bar__label">مبلغ قابل پرداخت</span>
                            <strong class="font-num-r">@{{ finalPriceSum }}</strong>
                        </div>
                        <a href="{{ route('basket.shipping') }}" class="sk-cta cart-mobile-bar__btn">
                            ادامه خرید
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>

                <div v-else class="cart-empty">
                    <div class="cart-empty__visual">
                        <img src="{{ asset('assets/site/images/empytcart5.png') }}" alt="سبد خرید خالی" loading="lazy">
                    </div>
                    <h2 class="cart-empty__title">سبد خرید شما خالی است</h2>
                    <p class="cart-empty__text">محصولات مورد علاقه‌تان را به سبد اضافه کنید و از خرید لذت ببرید.</p>
                    <a href="{{ route('product.get-all') }}" class="sk-cta">
                        <i class="bi bi-bag"></i>
                        مشاهده محصولات
                    </a>
                </div>
            </div>
        </div>
    </section>
@stop
@push('styles')
    <style>
        @media(max-width:576px) {
            .btn-to-top {
                bottom: 9rem;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/site/css/cart/tpl-checkout.css?v.20') }}">
    <script src="{{ asset('assets/site/js/tpl-sweetalert2.all.min.js') }}"></script>
@endpush
@push('scripts')
    <script src="{{ asset('assets/site/js/cart/tpl-checkout.js') }}"></script>
@endpush
@push('vue')
    @include('pages.cart._partials.checkout.vue')
@endpush
