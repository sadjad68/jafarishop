@extends('admin._layouts.master')
@section('title','مشخصه ها')
@section('content')
<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    مشخصه ها
                </h3>
                <div class="d-flex flex-md-row flex-column align-items-md-center justify-content-md-between w-100">
                    <a href="{{route('admin.specification.create')}}"
                        class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                        <i class="bi bi-plus-square-dotted d-flex h5 my-0 me-2"></i>
                        افزودن مشخصه
                    </a>
                    @component("admin.components.video-button")
                        @slot("type","product_spf")
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
                                    <th class="fw-bolder">
                                        دسته بندی
                                    </th>
                                    <th class="fw-bolder">
                                        وضعیت
                                    </th>
                                    <th class="fw-bolder">
                                        نوع
                                    </th>
                                    <th class="fw-bolder">
                                        فیلتر
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
                                @foreach($specification as $key=>$row)
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
                                        @if(count($row->categories) > 0)
                                        <x-admin.see-more-btn :data="$row->categories" :row="$row" />
                                        @else
                                        <span class="badge bg-label-primary" style="font-size: 11px">همگانی</span>
                                        @endif
                                    </th>

                                    <th>
                                        <span class="badge bg-label-{{$row->active_name['badge']}}"
                                            style="font-size: 11px">
                                            {{@$row->active_name['title']}}
                                        </span>
                                    </th>

                                    <th>
                                        <span class="badge bg-label-info" style="font-size: 11px">
                                            {{@$row->type_name}}
                                        </span>
                                    </th>

                                    <th>
                                        @if($row->is_filter)
                                        <span class="badge bg-label-primary" style="font-size: 11px">
                                            {{@$row->color_name['title']}}
                                        </span>
                                        @else
                                        <span class="badge bg-label-danger" style="font-size: 11px">
                                            <i class="bi bi-x-lg"></i>
                                        </span>

                                        @endif

                                    </th>


                                    <th>
                                        {{@$row->date}}
                                    </th>

                                    <th>
                                        <div class="btn-group" role="group">
                                            @if($row->type == "select")
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="مقادیر"
                                                href="{{route('admin.specification-value.index',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-list-columns color-custom2 fs-5"></i>
                                            </a>
                                            @endif
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.specification.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                                <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                   data-bs-title="حذف" onclick="confirmDeleteSpecification(
                                                       '{{route('admin.specification.delete',['id'=>$row->id])}}',
                                                       '{{route('admin.specification.delete-root',['id'=>$row->id])}}',
                                                       {{count($row->product_values)}}
                                                   )" href="#">
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
                    @slot("paginator",$specification)
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
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
<script type="text/javascript">
    function confirmDeleteSpecification(url, urlDeleteRoot, childrenCount) {
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
                        text: 'آیا میخواهید مقادیر این مشخصه هم پاک شود؟',
                        showCancelButton: true,
                        confirmButtonText: 'بله مقادیر نیز پاک شود',
                        cancelButtonText: 'بیخیال',
                    }).then((innerResult) => {
                        if (innerResult.isConfirmed) {
                            location.href = urlDeleteRoot;
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
