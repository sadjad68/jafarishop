@extends('pages.panel.master')
@section('logo')
<img src="{{$settings['footer_logo']}}" width="120" alt="{{$settings['siteName_fa']}}" title="{{$settings['siteName_fa']}}" class="logo-menu">
@endsection
@section('content')
@include('pages.panel._partials.page-header', [
    'icon' => 'bi-mailbox',
    'title' => 'تیکت‌ها',
    'badge' => '(5)',
    'subtitle' => 'پیگیری درخواست‌های پشتیبانی',
])

<div class="content">
    <div class="tickets p-2">
        <div class="header border-bottom mb-3 pb-2">
            <div class="row w-100 m-0">
                <div class="col-1 p-1 text-center">
                    <p class="font-bold font-small m-0">شماره</p>
                </div>
                <div class="col p-1 text-center">
                    <p class="font-bold font-small m-0">عنوان</p>
                </div>
                <div class="col p-1 text-center">
                    <p class="font-bold font-small m-0">وضعیت</p>
                </div>
                <div class="col-1 p-1 text-center d-md-block d-none">
                    <p class="font-bold font-small m-0">نمایش</p>
                </div>
            </div>
        </div>
        @php
            $tickets = [
                ['status' => 'پاسخ داده شده', 'class' => 'border-success text-success'],
                ['status' => 'بسته شده', 'class' => 'border-danger text-danger'],
                ['status' => 'درحال بررسی', 'class' => 'border-info text-info'],
                ['status' => 'در انتظار پاسخ', 'class' => 'border-warning text-warning'],
            ];
        @endphp
        @foreach($tickets as $ticket)
            <div class="item">
                <a href="#" class="font-re small m-0 d-block text-decoration-none">
                    <div class="row w-100 m-0 align-items-center">
                        <div class="col-1 p-1 text-center">
                            <p class="font-re small m-0">1</p>
                        </div>
                        <div class="col p-1 text-center">
                            <p class="font-re small m-0">عنوان تستی شماره یک</p>
                        </div>
                        <div class="col p-1 text-center">
                            <span class="badge bg-transparent border font-re fw-light {{ $ticket['class'] }}">
                                {{ $ticket['status'] }}
                            </span>
                        </div>
                        <div class="col-md-1 p-1 text-center d-md-block d-none">
                            <i class="bi bi-chevron-left"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
