@extends('admin._layouts.master')
@section('title')
دوره ها
@stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder">
                    دوره ها
                </h3>
                <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                    <a href="{{url('admin/course/create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن دوره
                    </a>
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
                                    <th>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    </th>
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
                                    <th class="fw-bolder" style="width:100px">
                                        وضعیت
                                    </th>
                                    <th class="fw-bolder" style="width:100px">
                                        نوع دوره
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
                                @foreach($course as $key=>$row)
                                <tr>
                                    <th>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                    </th>
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <span style="font-size: 11px">
                                            {{$row['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <img src="{{@$row->item_image}}" class="border shadow rounded"
                                            style="width:50px">
                                    </th>
                                    <th>
                                        <span class="badge  bg-label-primary" style="font-size: 11px">
                                            {{@$row->category->title}}
                                        </span>
                                    </th>
                                    <th>
                                        @if(@$row->active == 1)
                                        <span class="badge  bg-label-success" style="font-size: 11px">
                                            فعال
                                        </span>
                                        @else
                                        <span class="badge  bg-label-danger" style="font-size: 11px">
                                            غیر فعال
                                        </span>
                                        @endif
                                    </th>
                                    <th>
                                        @if(@$row->type == 1)
                                        <span class="badge  bg-label-info" style="font-size: 11px">
                                            حضوری
                                        </span>
                                        @else
                                        <span class="badge  bg-label-warning" style="font-size: 11px">
                                            آنلاین
                                        </span>
                                        @endif
                                    </th>
                                    <th>
                                        {{@$row->date}}
                                    </th>
                                    <th>

                                        <div class="btn-group" role="group">
                                            @include('admin.seo.seo.form', ['data' => $row])
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش" href="{{url('admin/course/edit/'.$row['id'])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="جلسات"
                                                href="{{route('admin.session.index',['course_id'=>$row['id']])}}">
                                                <i class="d-flex bi bi-easel color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('/admin/course/delete/{{ @$row['id'] }}')"
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
                    @if(count($course))
                    @include('admin.components.pagination.default',['paginator'=>$course])
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
{{-- search modal --}}
<div id="myModal" class="modal fade" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-custom border-custom shadow bg-white">
            <div class="modal-header px-3 py-2">
                <h4 class="m-0">
                    جستجو
                </h4>
                <button type="button" class="close btn px-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body p-2">
                <form id="cms-form" method="GET" action="{{URL::current()}}" class="m-0">
                    <div class="row w-100 m-0">
                        <div class="col-sm-6 p-2">
                            <div class="form-group">
                                <label>
                                    عنوان
                                </label>
                                <input name="title" id="" class="form-control rounded-custom">
                            </div>
                        </div>
                        <div class="col-sm-6 p-2">
                            <div class="form-group">
                                <label>
                                    دسته
                                </label>
                                <select name="parent_id" id="" class="form-select rounded-custom">
                                    @foreach ($category as $cat)
                                    <option value="{{$cat['id']}}">
                                        {{$cat['title']}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 ms-auto p-2">
                            <button type="submit" id="submitFormCms"
                                class="btn btn-custom rounded-custom w-fit px-5 ms-auto">
                                <i class="bi bi-search"></i>
                                جستجو
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
