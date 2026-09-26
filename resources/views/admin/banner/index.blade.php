@extends('admin._layouts.master')
@section('title','اسلایدر ها ')
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder">
                    اسلایدر ها
                </h3>
                <div class="d-flex align-items-center justify-content-between w-100">
                    <a href="{{route('admin.banner.create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن اسلایدر
                    </a>
                    <a href="{{route('admin.banner.sort')}}"
                       class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-arrows-move d-flex h5 my-0 me-2"></i>
                        مرتب سازی اسلایدر
                    </a>
                    @component("admin.components.video-button")
                        @slot("type","sliders")
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
                                        تصویر دسکتاپ
                                    </th>
                                    <th class="fw-bolder">
                                        تصویر موبایل
                                    </th>
                                    <th class="fw-bolder">
                                        عنوان
                                    </th>
                                    <th class="fw-bolder">
                                        وضعیت
                                    </th>
                                    <th class="fw-bolder">
                                        عملیات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-light">
                                @foreach($banner as $key=>$row)
                                <tr>
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <a target="_blank" href="{{@$row->image}}">
                                            <img src="{{@$row->image}}" class="border shadow rounded"
                                                style="width:50px" />
                                        </a>
                                    </th>
                                    <th>
                                        <a target="_blank" href="{{@$row->image_mobile}}">
                                            <img src="{{@$row->image_mobile}}" class="border shadow rounded"
                                                style="width:50px" />
                                        </a>
                                    </th>
                                    <th>
                                        {{$row->title}}
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
                                                href="{{route('admin.banner.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.banner.delete',['id'=>$row->id])}}')"
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
                    @slot("paginator",$banner)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
