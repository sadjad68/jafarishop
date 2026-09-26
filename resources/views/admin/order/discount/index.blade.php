@extends('admin._layouts.master')
@section('title','کدهای تخفیف')
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder mb-0">
                        کدهای تخفیف
                    </h3>
                    <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                        <a href="{{route('admin.discount.create')}}"
                           class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                            افزودن کد تخفیف
                        </a>
                        @component("admin.components.video-button")
                            @slot("type","discount")
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="form-control">
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
                                        مقدار
                                    </th>
                                    <th class="fw-bolder">
                                        کاربر
                                    </th>
                                    <th class="fw-bolder" style="width: 5%;">
                                        دسته بندی
                                    </th>
                                    <th class="fw-bolder" style="width: 5%;">
                                        برند
                                    </th>
                                    <th class="fw-bolder">
                                        وضعیت
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
                                @foreach($discounts as $key=>$row)
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
                                            <span class="badge bg-label-info">
                                                {{@$row->amount_name}}
                                            </span>
                                        </th>
                                        <th>
                                            {{@$row->user_id  ? @$row->user->full_name: 'همگانی'}}
                                        </th>
                                        <th style="width: 10cm;white-space:normal">
                                            @if (@$row->productCategories->count() > 0)
                                                <x-admin.see-more-btn :data="$row->productCategories" :row="$row" />
                                            @else
                                            -
                                            @endif
                                        </th>
                                        <th style="width: 5cm;white-space:normal">
                                            @if(count($row->brands) > 0)
                                                @foreach(@$row->brands->take(3) as $brand)
                                                    <span class="badge bg-label-primary m-1" style="font-size: 11px">{{$brand->title}}</span>
                                                @endforeach
                                                @if(@$row->brands->count() > 3)
                                                    <div id="brands-{{ $row->id }}" style="display: none;">
                                                        @foreach(@$row->brands->skip(3) as $brand)
                                                            <span class="badge bg-label-primary" style="font-size: 11px">{{$brand->title}}</span>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" id="see-{{ $row->id }}" class="btn btn-sm btn-link text-primary p-0" onclick="showMoreBrands({{ $row->id }})">
                                                        مشاهده بیشتر
                                                    </button>
                                                @endif
                                            @else
                                            -
                                            @endif
                                        </th>
                                        <th>
                                            <span class="badge bg-label-{{@$row->with_discount_name['badge']}}" style="font-size: 11px">
                                                {{@$row->with_discount_name['title']}}
                                            </span>
                                            <span class="badge bg-label-{{@$row->first_purchase_name['badge']}}" style="font-size: 11px">
                                                {{@$row->first_purchase_name['title']}}
                                            </span>
                                            <span class="badge bg-label-success" style="font-size: 11px">
                                                @if($row->pay_type == "brand")
                                                    {{ "اعمال بر اساس برند" }}
                                                @elseif($row->pay_type == "category")
                                                    {{ "اعمال بر اساس دسته‌بندی" }}
                                                @else
                                                    {{ "اعمال بر اساس محصول" }}
                                                @endif
                                            </span>
                                        </th>
                                        <th>
                                            {{@$row->date}}
                                        </th>
                                        <th>
                                            <div class="btn-group" role="group">
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="ویرایش"
                                                   href="{{route('admin.discount.edit',['id'=>$row->id])}}">
                                                    <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف"
                                                   onclick="confirmDelete('{{route('admin.discount.delete',['id'=>$row->id])}}')"
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
                            @slot("paginator",$discounts)
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
        @stack('modals')
    </div>
    <div id="myModal" class="modal fade" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
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
                            <div class="col-lg-4 p-2">
                                <div class="form-group">
                                    <label>
                                        عنوان
                                    </label>
                                    <input name="title" id="" class="form-control rounded-custom">
                                </div>
                            </div>

                            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 ms-auto p-2">
                                <button type="submit" id="submitFormCms" class="btn btn-success rounded-custom w-100">
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
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
    @include('admin._layouts.blocks.utils.confirmDelete')
    <script>
        function showMoreBrands(rowId) {
            const hiddenBrands = document.getElementById(`brands-${rowId}`);
            const seeButton = document.getElementById(`see-${rowId}`);
            if (hiddenBrands.style.display === 'none') {
                hiddenBrands.style.display = 'block';
                seeButton.textContent = 'مشاهده کمتر';
            } else {
                hiddenBrands.style.display = 'none';
                seeButton.textContent = 'مشاهده بیشتر';
            }
        }
    </script>
@endpush
