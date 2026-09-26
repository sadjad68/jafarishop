@extends('pages.panel.master')
@section('address','active')
@section('logo')
    <img src="{{$settings['footer_logo'] }}" width="120" alt="{{ $settings['siteName_fa'] }}" title="{{ $settings['siteName_fa'] }}" class="logo-menu">
@endsection
@section('content')
    <div id="app">
        <div v-if="loadingList" class="d-flex justify-content-center align-items-center" style="height: 50vh;">
            <div class="spinner-border text-info" role="status">
                <span class="visually-hidden">در حال بارگزاری آدرس‌ها...</span>
            </div>
        </div>
        <div v-if="!loadingList">
            <div class="panel-card panel-card--header mb-3">
                <div class="panel-card__head panel-card__head--split">
                    <div>
                        <h1 class="panel-card__title">
                            <span class="panel-card__title-icon"><i class="bi bi-map"></i></span>
                            آدرس‌ها
                        </h1>
                        <p class="panel-card__meta">مدیریت آدرس‌های تحویل سفارش</p>
                    </div>
                    <div class="panel-card__actions">
                        <button type="button" class="cart-link-success border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#exampleModal" @click="resetForm">
                            <i class="bi bi-plus-lg"></i>
                            افزودن آدرس جدید
                        </button>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="addresses">
                    <div class="row w-100 m-0 g-3">
                        <div class="col-sm-12 p-0" v-for="(location, index) in locations" :key="location.id">
                            <div class="address-item p-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <a @click="editAddress(location.id)" class="color-title d-flex align-items-center font-small font-re text-decoration-none" data-bs-toggle="collapse" :href="'#editAdress-' + location.id" role="button">
                                        <i class="bi bi-pencil d-flex me-1"></i>
                                        ویرایش
                                    </a>
                                    <div class="delete">
                                        <button type="button" @click="confirmDeleteAddress(location.id)" class="btn-delete d-flex align-items-center gap-1 border-0 bg-transparent text-danger small">
                                            <img width="15" src="{{ asset('assets/site/images/delete-panel.png') }}" alt="">
                                            حذف آدرس
                                        </button>
                                    </div>
                                </div>
                                <p class="font-md m-0 mt-3">@{{ location.state_name }} | @{{ location.city_name }}</p>
                                <p class="font-th m-0 small mt-2 text-muted">@{{ location.address }}</p>
                                <div class="collapse mt-3" :id="'editAdress-' + location.id">
                                    <div class="card card-body p-3 edit-card edit-info border-0">
                                        @include('pages.panel.addresses._partials.edit-form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg modal-add">
                            <div class="modal-content">
                                <div class="modal-header border-0 pb-0">
                                    <p class="modal-title fs-5 font-md" id="exampleModalLabel">افزودن آدرس جدید</p>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-2">
                                    @include('pages.panel.addresses._partials.form')
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-empty py-5" v-if="locations.length == 0">
                        <div class="panel-empty__icon"><i class="bi bi-geo-alt"></i></div>
                        <img class="w-100 mt-3" style="max-width: 160px;" src="{{ asset('assets/site/images/address.svg') }}" alt="">
                        <p class="font-bold m-0 mt-3">هنوز آدرس ثبت نکرده‌اید.</p>
                        <p class="small text-muted mt-2">برای تحویل سفارش، یک آدرس اضافه کنید</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vue-select.css') }}" />
    <script src="{{ asset('assets/site/js/tpl-sweetalert2.all.min.js') }}"></script>
@endpush
@push('scripts')
<script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect);
</script>
@endpush
@push('vue')
    @include('pages.panel.addresses._partials.vue')
@endpush
