@extends('admin._layouts.master')
@section('title',"مدیریت $type ها")
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder">
                        {{ $type . ' های سایت '}}
                    </h3>
                    <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                        <a href="{{route('admin.user.create')}}"
                           class="btn my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                            افزودن {{$type}}
                        </a>
                        <ul class="list-inline align-items-center m-0">
                            <li class="list-inline-item mx-0">
                                <a data-bs-target="#searchModal" data-bs-toggle="modal"
                                   class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
                                    <i class="bi bi-search d-flex my-0 me-2"></i>
                                    جستجوی پیشرفته
                                </a>
                            </li>
                            <li class="list-inline-item mx-0">
                                <a data-bs-target="#excelModal" data-bs-toggle="modal"
                                   class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
                                    <i class="bi bi-file-excel d-flex my-0 me-2"></i>
                                    خروجی اکسل
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table align-middle border-custom mb-0">
                            <thead class="text-start text-light">
                            <tr>
                                <th class="fw-bolder">
                                    ردیف
                                </th>
                                <th class="fw-bolder">
                                    #
                                </th>
                                <th class="fw-bolder">
                                    نام و نام خانوادگی
                                </th>
                                <th class="fw-bolder">
                                    شماره موبایل
                                </th>
                                <th class="fw-bolder">
                                    ایمیل
                                </th>
                                <th class="fw-bolder">
                                    نقش کاربری
                                </th>
                                <th class="fw-bolder">
                                    سطح دسترسی
                                </th>
                                <th class="fw-bolder">
                                    تاریخ تولد
                                </th>
                                <th class="fw-bolder">
                                    تاریخ عضویت
                                </th>
                                <th class="fw-bolder">
                                    عملیات
                                </th>
                            </tr>
                            </thead>
                            <tbody class="text-start text-light">
                            @foreach($user as $key=>$row)
                                <tr>
                                    <th>
                                        {{$key+1}}
                                    </th>
                                    <th>
                                        {{$row->id}}
                                    </th>
                                    <th>
                                        <img src="{{$row->getAvatar()}}" class="border shadow me-2 rounded-circle"
                                             width="30" height="30">
                                        <span style="font-size: 11px">
                                        {{$row->full_name}}
                                    </span>
                                    </th>
                                    <th>
                                        {{$row->mobile}}
                                    </th>
                                    <th>
                                        {{$row->email}}
                                    </th>
                                    <th>
                                        @foreach($row->userTypes as $user_type)
                                            <span class="badge bg-label-info" style="font-size: 11px">{{
                                        config('site.user_types')[$user_type->type] }}</span>
                                        @endforeach
                                    </th>
                                    <th>
                                        @foreach($row->roles as $role)
                                            <span class="badge bg-label-primary" style="font-size: 11px">{{ $role->name
                                        }}</span>
                                        @endforeach
                                    </th>
                                    <th>
                                        <span style="font-size: 14px">
                                            @if(@$row->birthday)
                                                {{ jdate('d F Y', $row->birthday) }}
                                            @else
                                                -- -- --
                                            @endif
                                        </span>
                                    </th>
                                    <th>
                                        <span style="font-size: 14px">
                                            {{@$row->date}}
                                        </span>
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="ویرایش"
                                               href="{{route('admin.user.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            @include('admin.user.user.change-password',['id'=>$row->id])
                                            @if(!$row->userTypes->contains('type', 'Admin'))
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف"
                                                   onclick="confirmDelete('{{route('admin.user.delete',['id'=>$row->id])}}')"
                                                   href="#">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                            @elseif(($row->userTypes->contains('type', 'Admin')) && count($user) > 1)
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف"
                                                   onclick="confirmDelete('{{route('admin.user.delete',['id'=>$row->id])}}')"
                                                   href="#">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                            @endif
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="سفارش ها"
                                               href="{{route('admin.order.index',['filter'=>true,'user_id'=>$row->id])}}">
                                                @if(count($row->orders) > 0 )
                                                    <i class="d-flex bi bi-cart-dash-fill color-custom2 fs-5"></i>
                                                @else

                                                    <i class="d-flex bi bi-cash-stack color-custom2 fs-5"></i>
                                                @endif
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="تخفیف ها"
                                               href="{{route('admin.discount.index',['filter'=>true,'user_id'=>$row->id])}}">
                                                <i class="d-flex bi bi-percent color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="آدرس ها"
                                               href="{{route('admin.address.index',['filter'=>true,'user_id'=>$row->id])}}">
                                                @if(count($row->addresses) > 0)
                                                    <i class="d-flex bi bi-pin-fill color-custom2 fs-5"></i>
                                                @else
                                                    <i class="d-flex bi bi-pin-map color-custom2 fs-5"></i>
                                                @endif
                                            </a>
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @component("admin.components.pagination.default")
                        @slot("paginator",$user)
                    @endcomponent
                </div>
            </div>
        </div>
        @stack('modals')
    </div>
    @include('admin.user.user.search')
    @include('admin.user.user.excel')
@endsection
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
    @include('admin._layouts.blocks.utils.confirmDelete')
@endpush
