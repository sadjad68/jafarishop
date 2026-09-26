@extends('admin._layouts.master')

@section('title')
ریدایرکت
@stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header mb-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-start">
                            <h3 class="fw-bolder mb-0 d-flex align-items-center justify-content-center justify-content-md-start">
                                <i class="bi bi-arrow-left-right me-2 text-primary"></i>
                                مدیریت ریدایرکت‌ها
                            </h3>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0 gap-2">
                            <a href="{{route('admin.redirect.create')}}"
                               class="btn btn-primary rounded-pill px-4 d-flex align-items-center shadow-sm">
                                <i class="bi bi-plus-circle me-2"></i>
                                افزودن ریدایرکت
                            </a>
                            <a data-bs-target="#searchModal" data-bs-toggle="modal"
                               class="btn btn-outline-secondary rounded-pill px-4 d-flex align-items-center">
                                <i class="bi bi-search me-2"></i>
                                جستجوی پیشرفته
                            </a>
                            @component("admin.components.video-button")
                                @slot("type","seo_settings")
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <div class="row align-items-end">
                        <div class="col-lg-8">
                            <form method="POST" action="{{route('admin.redirect.import')}}" enctype="multipart/form-data" class="m-0">
                                @csrf
                                <div class="row align-items-end g-3">
                                    <div class="col-md-8">
                                        <div class="form-group mb-0">
                                            <label class="form-label fw-bold mb-2">وارد کردن دسته‌جمعی (Excel)</label>
                                            <x-cms-input
                                                name="excel"
                                                label=""
                                                type="file"
                                                class="form-control-lg"
                                                :validations="['requiredCms']"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success w-100 rounded-custom py-2 shadow-sm d-flex align-items-center justify-content-center">
                                            <i class="bi bi-file-earmark-arrow-up me-2"></i>
                                            شروع عملیات واردسازی
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                            <div class="border-start-lg ps-lg-4">
                                <p class="small text-muted mb-2">هنوز فایلی آماده نکرده‌اید؟</p>
                                <a href="{{asset('assets/admin/redirect-example.xlsx')}}"
                                   download=""
                                   class="btn btn-link btn-sm text-decoration-none fw-bold">
                                    <i class="bi bi-download me-1"></i>
                                    دانلود فایل نمونه اکسل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card-block row">
            <div class="col-sm-12 col-lg-12 col-xl-12">
                <form class="form-control">
                    <div class="table-responsive d-flex align-items-center">
                        <table id="myDataTable" class="table align-middle border-custom mb-0">
                            <thead class="text-center text-light">
                                <tr>
                                    {{-- <th>--}}
                                        {{-- <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault">--}}
                                        {{-- </th>--}}
                                    <th class="fw-bolder">
                                        #
                                    </th>
                                    <th class="fw-bolder">
                                        آدرس قدیم
                                    </th>
                                    <th class="fw-bolder">
                                        آدرس جدید
                                    </th>
                                    <th class="fw-bolder">
                                   نوع
                                    </th>
                                    <th class="fw-bolder" style="width: 10%;">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder">
                                        عملیات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-light">
                                @foreach($redirect as $key=>$row)
                                <tr>
                                    {{-- <th>--}}
                                        {{-- <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked">--}}
                                        {{-- </th>--}}
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th style="font-size: 14px;direction: ltr">

                                        {{$row['old_address']}}

                                    </th>
                                    <th style="font-size: 14px;direction: ltr">

                                        {{$row['new_address']}}

                                    </th>
                                    <th style="font-size: 14px;direction: ltr">

                                        {{$row['type']}}

                                    </th>
                                    <th>
                                        {{@$row->date}}
                                    </th>

                                    <th>
                                        <div class="btn-group" role="group">
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.redirect.delete',['id'=>$row->id])}}')"
                                                href="#">
                                                <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                            </a>
                                        </div>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @component("admin.components.pagination.default")
                    @slot("paginator",$redirect)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
</div>
@include('admin.seo.redirect.search')
@endsection
@push('scripts')
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
@push('styles')
    <style>
        /* استایل‌های تکمیلی برای زیبایی بیشتر */
        .rounded-custom { border-radius: 10px; }
        .btn-primary { background-color: #4e73df; border-color: #4e73df; }
        .bg-light { background-color: #f8f9fc !important; }
        .form-label { font-size: 0.9rem; color: #4e5e6a; }
        .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }

        /* افکت هاور برای دکمه ها */
        .btn:hover {
            transform: translateY(-1px);
            transition: all 0.2s;
        }

        @media (min-width: 992px) {
            .border-start-lg {
                border-right: 1px solid #dee2e6 !important; /* در حالت راست‌چین */
            }
        }
    </style>
@endpush
