@extends('admin._layouts.master')

@section('title')
مطالب
@stop
@section('content')
<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    مطالب
                </h3>
                <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                    <a href="{{route('admin.blog.create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن مطلب
                    </a>
                    <ul class="list-inline align-items-center m-0">
                        <li class="list-inline-item mx-0">
                            <a data-bs-target="#searchModal" data-bs-toggle="modal"
                               class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
                                <i class="bi bi-search d-flex my-0 me-2"></i>
                                جستجوی پیشرفته
                            </a>
                        </li>
                    </ul>
                    @component("admin.components.video-button")
                        @slot("type","posts")
                    @endcomponent
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
                                        عنوان
                                    </th>
                                    <th class="fw-bolder" style="width: 10%;">
                                        تصویر
                                    </th>
                                    <th class="fw-bolder" style="width:100px">
                                        دسته
                                    </th>
                                    <th class="fw-bolder" style="width: 5cm">
                                        خدمات
                                    </th>
                                    <th class="fw-bolder">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder" style="width:250px">
                                        عملیات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-light">
                                @foreach($blog as $key=>$row)
                                <tr>
                                    {{-- <th>--}}
                                        {{-- <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked">--}}
                                        {{-- </th>--}}
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <span style="font-size: 11px">
                                            {{$row['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <img src="{{@$row->getItemImage()}}" class="border shadow rounded"
                                            style="width:50px">
                                    </th>
                                    <th>
                                        <span class="badge bg-label-primary">
                                            {{@$row->category->title}}
                                        </span>
                                    </th>
                                    <th style="width: 5cm;white-space:normal">
                                        @if ($row->services?->isNotEmpty())
                                            <x-admin.see-more-btn :data="$row->services" :row="$row" />
                                        @endif
                                    </th>
                                    <th>
                                        {{@$row->publish}}
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">
                                            @include('admin.seo.seo.form', ['data' => $row])
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.blog.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.blog.delete',['id'=>$row->id])}}')"
                                                href="#">
                                                <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="مشاهده" target="_blank"
                                               href="{{ \App\Library\SiteUrl::blog($row) }}">
                                                <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                            </a>
                                            @include('admin.components.admin.copy-button', ['url' => parse_url(\App\Library\SiteUrl::blog($row), PHP_URL_PATH)])

                                        </div>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @component("admin.components.pagination.default")
                    @slot("paginator",$blog)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
    @stack('modals')
</div>
@include('admin.blog.blog.search')
{{--<div id="myModal" class="modal fade" style="display: none;" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-dialog-scrollable modal-lg">--}}
{{--        <div class="modal-content rounded-custom border-custom shadow bg-white">--}}
{{--            <div class="modal-header px-3 py-2">--}}
{{--                <h4 class="m-0">--}}
{{--                    جستجو--}}
{{--                </h4>--}}
{{--                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">--}}
{{--                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>--}}
{{--                </button>--}}
{{--            </div>--}}
{{--            <div class="modal-body p-2">--}}
{{--                <form id="cms-form" method="GET" action="{{URL::current()}}" class="m-0">--}}
{{--                    <div class="row w-100 m-0">--}}
{{--                        <div class="col-lg-4 p-2">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>--}}
{{--                                    عنوان--}}
{{--                                </label>--}}
{{--                                <input name="title" id="" class="form-control rounded-custom">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-lg-4 p-2">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>--}}
{{--                                    دسته--}}
{{--                                </label>--}}
{{--                                <select name="parent_id" id="" class="form-select rounded-custom">--}}
{{--                                    @foreach ($category as $cat)--}}
{{--                                    <option value="{{$cat['id']}}">--}}
{{--                                        {{$cat['title']}}--}}
{{--                                    </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">--}}
{{--                            <button type="submit" id="submitFormCms" class="btn btn-success rounded-custom w-100">--}}
{{--                                <i class="bi bi-search"></i>--}}
{{--                                جستجو--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
