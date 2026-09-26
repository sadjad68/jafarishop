@extends('pages.panel.master')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.panel._partials.page-header', [
    'icon' => 'bi-columns-gap',
    'title' => 'پکیج‌های من',
    'subtitle' => 'دسترسی به پکیج‌های خریداری‌شده',
])

<div class="content">
    <div class="packages">
        <div class="row w-100 m-0 g-3">
            @foreach(['package1.webp', 'package2.webp', 'package3.webp'] as $packageImage)
                <div class="col-xxl-4 col-sm-6 p-0">
                    <div class="item-package">
                        <img src="assets/site/images/{{ $packageImage }}" class="w-100" alt="package" title="package" loading="lazy">
                        <p class="font-bold m-0 text-center my-3">پکیج فرمالیته با پرسنل</p>
                        <a href="#" class="sk-cta w-100 py-2 text-center d-flex align-items-center justify-content-center text-decoration-none">
                            <i class="bi bi-eye d-flex me-2"></i>
                            مشاهده پکیج
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
