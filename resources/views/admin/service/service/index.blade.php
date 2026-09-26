@extends('admin._layouts.master')
@section('title','خدمات')
@section('content')
<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder">
                    خدمات
                </h3>
                <div class="d-flex align-items-center justify-content-between w-100">
                    <a href="{{route('admin.service.create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن خدمات
                    </a>
                    <a href="{{route('admin.service.sort')}}"
                       class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-arrows-move d-flex h5 my-0 me-2"></i>
                        مرتب سازی
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
                        @slot("type","services")
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
                        <table class="table align-middle border-custom mb-0">
                            <thead class="text-center text-light">
                                <tr>
                                    <th class="fw-bolder">
                                        #
                                    </th>
                                    <th class="fw-bolder">
                                        عنوان
                                    </th>
                                    <th class="fw-bolder">
                                        تصویر
                                    </th>
                                    <th class="fw-bolder">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder" style="width: 1rem;">
                                        وضعیت نمایش
                                    </th>
                                    <th class="fw-bolder" style="width: 20%">
                                        عملیات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center text-light">
                                @foreach($services as $key=>$row)
                                <tr>
                                    <th>
                                        {{$key + 1}}
                                    </th>
                                    <th>
                                        <span class="badge bg-label-primary" style="font-size: 11px">
                                            {{$row->title}}
                                        </span>
                                    </th>
                                    <th>
                                        <img src="{{$row->image}}" class="border shadow rounded" style="width:50px">
                                    </th>
                                    <th>
                                        {{$row->date}}
                                    </th>
                                    <th>
                                        @foreach($row->show_name as $label)
                                        <span class="badge bg-label-success" style="font-size: 11px">
                                            {{$label}}
                                        </span>
                                        @endforeach
                                    </th>
                                    <th>
                                        <div class="btn-group" role="group">
                                            @include('admin.seo.seo.form', ['data' => $row])
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.service.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="سوالات متداول"
                                               href="{{route('admin.service-faq.index',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-question color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="حذف" onclick="confirmDeleteService(
                                                       '{{route('admin.service.delete',['id'=>$row->id])}}',
                                                       '{{route('admin.service.delete-root',['id'=>$row->id])}}',
                                                       {{count($row->children)}}
                                                   )" href="#">
                                                <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="نمونه کارها"
                                                href="{{url('admin/worksample?service_id='.$row->id)}}">
                                                <i class="d-flex bi bi-file-image color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="نرخ ها" href="{{url('admin/fee?service_id='.$row->id)}}">
                                                <i class="d-flex bi bi-currency-dollar color-custom2 fs-5"></i>
                                            </a>
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                               data-bs-title="مشاهده" target="_blank"
                                               href="{{ route('service.detail', ['url' => $row['url']]) }}">
                                                <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                            </a>
                                            @include('admin.components.admin.copy-button', ['url' => parse_url(route('service.detail', ['url' => $row['url']]), PHP_URL_PATH)])

                                        </div>
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($services))
                    @include('admin.components.pagination.default',['paginator'=>$services])
                    @endif
                </form>
            </div>
        </div>
    </div>
    @stack('modals')
</div>
@include('admin.service.service.search')
@endsection
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
<script type="text/javascript">
    function confirmDeleteService(url, urlDeleteRoot, childrenCount) {
        const swalWithButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-secondary',
            },
            buttonsStyling: false,
        });
        const customSecondConfigrmSwal = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                denyButton: 'btn btn-warning mt-2',
                cancelButton: 'btn btn-secondary w-50 mt-2',
            },
            buttonsStyling: false,
        });
        swalWithButtons.fire({
            icon: 'warning',
            text: "آیا از حذف آیتم مطمئن هستید؟",
            showCancelButton: true,
            confirmButtonText: 'بله مطمئن هستم',
            cancelButtonText: 'لغو',
            showCloseButton: true,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                if (childrenCount > 0) {
                    customSecondConfigrmSwal.fire({
                        icon: 'question',
                        text: 'آیا میخواهید زیرمجموعه های این دسته هم پاک شود؟',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: 'بله زیرمجموعه ها نیز پاک شود',
                        denyButtonText: 'فقط این دسته پاک شود و زیرمجموعه ها بدون والد شوند',
                        cancelButtonText: 'بیخیال',
                    }).then((innerResult) => {
                        if (innerResult.isConfirmed) {
                            location.href = urlDeleteRoot;
                        } else if (innerResult.isDenied) {
                            location.href = url;
                        }
                    });
                } else {
                    location.href = url;
                }
            }
        });
    }
</script>
@endpush
