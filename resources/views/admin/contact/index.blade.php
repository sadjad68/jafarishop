@extends('admin._layouts.master')

@section('title')
تماس با ما
@stop
@section('content')
<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    تماس با ما
                </h3>

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
                                        نام
                                    </th>
                                    <th class="fw-bolder" style="width:100px">
                                        شماره همراه
                                    </th>
                                    <th class="fw-bolder">
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
                                @foreach($contacts as $key=>$row)
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
                                        <span style="font-size: 11px">
                                            {{$row['name']}}
                                        </span>
                                    </th>
                                    <th>
                                        <span class="badge bg-label-primary">
                                            {{$row['mobile']}}
                                        </span>
                                    </th>
                                    <th>
                                        {{@$row->date}}
                                    </th>
                                    <th>
                                        <span style="font-size: 11px">
                                            {{$row['status'] ? "بررسی شد" : "بررسی نشده"}}
                                        </span>
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">

                                            <a class="d-flex me-2 align-items-center" id="seoBtn{{$row->id}}"
                                                data-bs-target="#conatctModal{{$row->id}}" data-bs-toggle="modal">
                                                <i class="d-flex bi bi-ear color-custom2 fs-5"></i>
                                            </a>
                                            @push('modals')
                                            {{-- modal --}}
                                            <div id="conatctModal{{$row->id}}" class="modal fade" style="display: none;"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                    <div
                                                        class="modal-content rounded-custom border-custom shadow bg-white">
                                                        <div class="modal-header px-3 py-2">
                                                            <button type="button" class="close btn px-0"
                                                                data-bs-dismiss="modal" aria-label="Close">
                                                                <i class="bi bi-x-lg d-flex" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body p-3">
                                                            <h6 class="modal-title fw-bolder mb-2"
                                                                id="contactModalLabel">
                                                                {{@$row['name']}}
                                                                -
                                                                شماره همراه :
                                                                {{@$row['mobile']}}
                                                                -
                                                                عنوان :
                                                                {{@$row['title']}}
                                                                -
                                                                {{@$row->date}}
                                                            </h6>
                                                            <div class="desc" style="white-space: pre-line;">
                                                                {!! @$row['message'] !!}
                                                            </div>
                                                            <hr>
                                                            @if($row['status'] != 1)
                                                            <div class="w-100 pe-0">
                                                                <a type="button"
                                                                    href="{{route('admin.contact.status',['id'=>$row->id])}}"
                                                                    class="btn btn-custom rounded-custom w-fit px-3 py-2 float-end">
                                                                    بررسی شد
                                                                </a>
                                                            </div>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            @endpush

                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف"
                                                onclick="confirmDelete('{{route('admin.contact.delete',['id'=>$row->id])}}')"
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
                    @slot("paginator",$contacts)
                    @endcomponent
                </form>
            </div>
        </div>
    </div>
    @stack('modals')
</div>

@endsection
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
