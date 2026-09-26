@extends('admin._layouts.master')

@section('title')
    دسته بندی محصولات
@stop
@section('content')
    <div class="body d-flex py-3" id="cms-form">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <h3 class="fw-bolder mb-0">
                        دسته بندی محصولات
                    </h3>
                    <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                        <a href="{{route('admin.product-category.create')}}"
                           class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                            افزودن دسته بندی محصولات
                        </a>
                        <a href="{{route('admin.product-category.sort')}}"
                           class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                            <i class="bi bi-arrows-move d-flex h5 my-0 me-2"></i>
                            مرتب سازی دسته بندی محصولات
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
                            @slot("type","product_category")
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
                                    <th class="fw-bolder">
                                        تصویر
                                    </th>
                                    <th class="fw-bolder">
                                        وضعیت نمایش
                                    </th>
                                    <th class="fw-bolder">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder">
                                        عملیات
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="text-center text-light">
                                @foreach($product_category as $key=>$row)
                                    <tr>
                                        <th>
                                            {{$key + 1}}
                                        </th>
                                        <th>
                                        <span style="font-size: 14px">
                                            {{$row['title']}}
                                        </span>
                                        </th>
                                        <th>
                                            <img src="{{@$row->getImage()}}" class="border shadow rounded"
                                                 style="width:50px">
                                        </th>
                                        <th>
                                        <span class="badge bg-label-{{$row->active_name['badge']}}"
                                              style="font-size: 11px">
                                            {{@$row->active_name['title']}}
                                        </span>
                                            <span class="badge bg-label-{{$row->show_site_name['badge']}}"
                                                  style="font-size: 11px">
                                            {{@$row->show_site_name['title']}}
                                        </span>
                                            <span class="badge bg-label-{{$row->first_page_name['badge']}}"
                                                  style="font-size: 11px">
                                            {{@$row->first_page_name['title']}}
                                        </span>
                                        </th>
                                        <th>
                                            {{@$row->date}}
                                        </th>
                                        <th>

                                            <div class="btn-group" role="group">
                                                @include('admin.seo.seo.form', ['data' => $row])
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="ویرایش"
                                                   href="{{route('admin.product-category.edit',['id'=>$row->id])}}">
                                                    <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف" onclick="confirmDeleteBlogCategory(
                                                       '{{route('admin.product-category.delete',['id'=>$row->id])}}',
                                                       '{{route('admin.product-category.delete-root',['id'=>$row->id])}}',
                                                       {{count($row->children)}}
                                                   )" href="#">
                                                    <i class="d-flex bi bi-trash3 color-custom2 fs-5"></i>
                                                </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="مشاهده" target="_blank"
                                                   href="{{ \App\Library\SiteUrl::category($row) }}">
                                                    <i class="d-flex bi bi-eye color-custom2 fs-5"></i>
                                                </a>
                                                @include('admin.components.admin.copy-button', ['url' => parse_url(\App\Library\SiteUrl::category($row), PHP_URL_PATH)])

                                            </div>
                                        </th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @component("admin.components.pagination.default")
                            @slot("paginator",$product_category)
                        @endcomponent
                    </form>
                </div>
            </div>
        </div>
        @stack('modals')
    </div>
    {{-- search modal --}}
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
@include('admin.product.product-category.search')
@push('scripts')
    <script src="{{asset('assets/admin/js/vue.js')}}"></script>
    <script src="{{asset('assets/admin/js/validations.js')}}"></script>
    <script type="text/javascript">
        function confirmDeleteBlogCategory(url, urlDeleteRoot, childrenCount) {
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
