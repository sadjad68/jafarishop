@extends('admin._layouts.master')

@section('title')
روش های ارسال
@stop
@section('content')
<div class="body d-flex py-3">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    روش های ارسال
                </h3>
                <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                    <a href="{{route('admin.shipping-method.create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن روش
                    </a>
                    <div class="d-flex align-items-center">
                        @component("admin.components.video-button")
                            @slot("type","shipping")
                        @endcomponent
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
                                        عنوان
                                    </th>
                                    <th class="fw-bolder">
                                        قیمت
                                    </th>
                                    <th class="fw-bolder" style="width: 10%;">
                                        تاریخ
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
                                @foreach($methods as $key=>$row)
                                <tr>
                                    {{-- <th>--}}
                                        {{-- <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckChecked">--}}
                                        {{-- </th>--}}
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <span style="font-size: 14px">
                                            {{$row['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <span style="font-size: 14px">
                                            @if($row->type !== 'chapar')
                                            {{intval($row['price']) != 0 ? number_format($row['price']).' تومان ' : 'ارسال رایگان'}}
                                            @else
                                                تعرفه چاپار
                                            @endif
                                        </span>
                                    </th>

                                    <th>
                                        {{@$row->date}}
                                    </th>
                                    <th>
                                        <span class="badge bg-label-{{$row->status_name['badge']}}"
                                            style="font-size: 11px">
                                            {{@$row->status_name['title']}}
                                        </span>

                                        <span class="badge bg-label-{{$row->type_name['badge']}}"
                                              style="font-size: 11px">
                                            {{@$row->type_name['title']}}
                                        </span>
                                        <span class="badge bg-label-{{$row->freight_balance_name['badge']}}"
                                              style="font-size: 11px">
                                            {{@$row->freight_balance_name['title']}}
                                        </span>
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.shipping-method.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.shipping-method.delete',['id'=>$row->id])}}')"
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
                    @slot("paginator",$methods)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
    @stack('modals')
</div>

@endsection
@push('scripts')
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
