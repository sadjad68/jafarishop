@extends('layouts.main.master')
@section('logo')
    <img src="{{ $settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('title_seo')آدرس و ارسال@endsection
@section('description_seo')آدرس خود را وارد کنید@endsection
@section('content')
<section class="cart cart-page mx-md-4 mt-md-3 mt-4 mb-5">
    <div class="container" id="app" v-cloak>
        @include('pages.cart._partials.steps', ['activeStep' => 2])

        <div class="checkout row w-100 m-0">
            <div class="col-xl-9 col-lg-8 ps-0 pe-0 pe-lg-2 mt-4">
                <div class="cart-card cart-card--addresses">
                    <div class="cart-card__head">
                        <div>
                            <h1 class="cart-card__title">آدرس‌های من</h1>
                            <p class="cart-card__meta">آدرس تحویل و روش ارسال را انتخاب کنید</p>
                        </div>
                        <button type="button" class="cart-link-success" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="resetForm">
                            <i class="bi bi-plus-lg"></i>
                            آدرس جدید
                        </button>
                    </div>

                    <div v-if="loadingList == true" class="cart-card__loading">
                        @include('layouts.common.loading')
                    </div>
                    <div v-else>
                        <div v-if="locations.length != 0">
                            @include('pages.cart._partials.address.address-list')
                        </div>
                        <div v-else class="cart-inline-empty">
                            <img src="{{ asset('assets/site/images/emptyadress.png') }}" alt="آدرسی ثبت نشده" loading="lazy">
                            <p class="cart-inline-empty__title">آدرسی برای شما ثبت نشده است</p>
                            <p class="cart-inline-empty__text">برای ادامه خرید، یک آدرس جدید اضافه کنید.</p>
                        </div>
                    </div>

                    @include('pages.cart._partials.address.add-modal')
                    @include('pages.cart._partials.address.shipping')
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 pe-0 ps-0 ps-lg-2 mt-4">
                @include('pages.cart._partials.address.price-box')
            </div>
        </div>

        <div class="cart-mobile-bar d-lg-none">
            <div class="cart-mobile-bar__price" v-if="priceCart">
                <span class="cart-mobile-bar__label">مبلغ قابل پرداخت</span>
                <strong class="font-num-r">@{{ priceCart }}</strong>
            </div>
            <a href="{{ route('basket.payment') }}" class="sk-cta cart-mobile-bar__btn" @click.prevent="goToPayment">
                ادامه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
</section>
@stop
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vue-select.css') }}">
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
    <script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
    <script>
        Vue.component('v-select', VueSelect.VueSelect);
    </script>
@endpush
@push('vue')
    @include('pages.cart._partials.address.vue')
@endpush
