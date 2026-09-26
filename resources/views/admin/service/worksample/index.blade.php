@extends('admin._layouts.master')
@section('title')
نمونه کارها
@stop
@section('content')

<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder">
                    نمونه کارها{{@$service ? 'ی ' .@$service->title : ''}}
                </h3>
                <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                    <a href="{{route('admin.worksample.create',['service_id'=>@$service->id])}}"
                        class="btn my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-plus-square-dotted d-flex my-0 me-2"></i>
                        افزودن نمونه کار
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
                        @slot("type","work_samples")
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
                                    <th class="fw-bolder">
                                        #
                                    </th>
                                    <th class="fw-bolder">
                                        عنوان
                                    </th>
                                    <th class="fw-bolder" style="width: 10%;">
                                        تصویر
                                    </th>
                                    <th class="fw-bolder">
                                        خدمات
                                    </th>
                                    <th class="fw-bolder" style="width: 10%;">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder">
                                        وضعیت
                                    </th>
                                    <th class="fw-bolder" style="width:250px">
                                        عملیات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-light">
                                @foreach($samples as $key=>$row)
                                <tr>
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <span style="font-size: 11px">
                                            {{$row['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <img src="{{@$row->getImage()}}" class="border shadow rounded"
                                            style="width:50px">
                                    </th>
                                    <th>
                                            <x-admin.see-more-btn :data="$row->services" :row="$row" />
                                    </th>
                                    <th>
                                        {{@$row->date}}
                                    </th>
                                    <th>
                                        <span class="badge bg-label-{{$row->first_page_name['badge']}}"
                                            style="font-size: 11px">
                                            {{@$row->first_page_name['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">

                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.worksample.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.worksample.delete',['id'=>$row->id])}}')"
                                                href="#">
                                                <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                            </a>
                                            @if($row->has_page == 1)
                                                @include('admin.seo.seo.form', ['data' => $row])
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="مشاهده" target="_blank"
                                                   href="{{ route('portfolio.detail', ['url' => $row['url']]) }}">
                                                    <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                                </a>
                                                @include('admin.components.admin.copy-button', ['url' => parse_url(route('portfolio.detail', ['url' => $row['url']]), PHP_URL_PATH)])

                                            @endif
                                        </div>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @component("admin.components.pagination.default")
                    @slot("paginator",$samples)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
    @stack('modals')
</div>
@include('admin.service.worksample.search')
@endsection
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
