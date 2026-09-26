@extends('pages.panel.master')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.panel._partials.page-header', [
    'icon' => 'bi-suit-heart',
    'title' => 'علاقه‌مندی‌ها',
    'subtitle' => 'محصولاتی که ذخیره کرده‌اید',
])

<div class="content">
    <div class="row w-100 m-0">
        <div class="col-xl-12 p-0">
            <div class="favorites">
                <div class="row w-100 m-0 g-3">
                    @foreach(['product10.png', 'product11.png', 'product3.png', 'product4.png'] as $productImage)
                        <div class="col-xxl-6 col-12 p-0">
                            <a href="#" class="color-title text-decoration-none">
                                <div class="favorite-item">
                                    <button type="button" class="btn btn-trash p-0">
                                        <i class="bi bi-trash d-flex"></i>
                                    </button>
                                    <div class="row w-100 m-0">
                                        <div class="col-xl-2 col-md-2 col-3 p-2">
                                            <img src="assets/site/images/{{ $productImage }}" class="w-100" alt="product" title="product" loading="lazy">
                                        </div>
                                        <div class="col-xl-10 col-md-10 col-9 p-2 align-self-center">
                                            <p class="font-bold mb-1 small pe-4">ژل لیفت ابرو جین اشلی 250 سی سی</p>
                                            <p class="font-th m-0 font-small text-muted">دسته‌بندی: آرایشی و بهداشتی</p>
                                            <div class="price mt-3 d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="old-price">
                                                        <p class="m-0 font-num-r">140,000</p>
                                                    </div>
                                                    <p class="font-bold fw-bold font-num-r m-0">200,000 تومان</p>
                                                </div>
                                                <button type="button" class="sk-cta btn-sm py-2 px-3 font-re d-flex align-items-center">
                                                    <i class="bi bi-cart3 d-flex me-1"></i>
                                                    اضافه به سبد
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
