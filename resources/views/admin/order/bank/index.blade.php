@extends('admin._layouts.master')

@section('title')
درگاه های بانکی
@stop
@section('content')
<div class="body d-flex py-3" id="cms-form">
    <div class="container-fluid">
        <div class="page-header">
            <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                <h3 class="fw-bolder mb-0">
                    درگاه های بانکی
                </h3>
                <div class="align-items-center">
                    @component("admin.components.video-button")
                        @slot("type","bank")
                    @endcomponent

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <button type="button"
                class="btn me-2 p-0 bg-transparent border-0 mt-4 mb-3 fs-6 shadow-none d-flex align-items-center gap-2 text-info "
                data-bs-toggle="modal"
                data-bs-target="#exampleModal"
                data-bs-title="راهنمای درگاه ها" title="راهنمای درگاه ها">
            <i class="bi bi-info-square d-flex"></i>
            راهنمای درگاه ها
        </button>
        <div class="alert alert-info" role="alert">
            برای تغییر ترتیب نمایش، ردیف‌های جدول را با درگ اند دراپ جابه‌جا کنید.
        </div>
        <div class="card-block row">
            <div class="col-sm-12 col-lg-12 col-xl-12">
                <form class="form-control">
                    <div class="table-responsive d-flex align-items-center">
                        <table id="myDataTable" class="table align-middle border-custom mb-0">
                            <thead class="text-center text-light">
                                <tr>
                                    <th class="fw-bolder" style="width:40px;"></th>
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
                                        وضعیت نمایش
                                    </th>
                                    <th class="fw-bolder">
                                        تعرفه درگاه
                                    </th>
                                    <th class="fw-bolder">
                                        تاریخ
                                    </th>
                                    <th class="fw-bolder" style="width:250px">
                                        عملیات
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="bank-sortable" class="text-center text-light">
                                @foreach($banks as $key=>$row)
                                <tr data-id="{{$row->id}}" style="cursor: grab;">
                                    <td class="drag-handle">
                                        <i class="bi bi-grip-vertical text-muted fs-5"></i>
                                    </td>
                                    <td class="row-number">
                                        {{$key + 1}}
                                    </td>
                                    <td>
                                        <span style="font-size: 11px">
                                            {{$row['title']}}
                                        </span>
                                    </td>
                                    <td>
                                        <img src="{{@$row->item_image}}" class="border shadow rounded"
                                            style="width:50px">
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{$row->status_name['badge']}}"
                                            style="font-size: 11px">
                                            {{@$row->status_name['title']}}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 11px">
                                            {{
                                            \App\Modules\General\Helper\NumberHelper::latin2PersianDigit(intval($row->gateway_tariff
                                            ?? 0)) }}٪
                                        </span>
                                    </td>
                                    <td>
                                        {{@$row->date}}
                                    </td>

                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="d-flex me-2 align-items-center" data-bs-toggle="tooltip"
                                                data-bs-title="ویرایش"
                                                href="{{route('admin.bank.edit',['id'=>$row->id])}}">
                                                <i class="d-flex bi bi-pencil-square color-custom2 fs-5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @stack('modals')
</div>
@include('admin.order.bank.bank-modal')

@endsection
@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #bank-sortable tr.ui-sortable-helper {
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        #bank-sortable tr.ui-sortable-placeholder {
            visibility: visible !important;
            background: #f0eeff;
            height: 60px;
        }
        #bank-sortable .drag-handle {
            cursor: grab;
        }
    </style>
@endpush
@push('scripts')
<script src="{{asset('assets/admin/js/vue.js')}}"></script>
<script src="{{asset('assets/admin/js/validations.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery-ui.min.js')}}"></script>
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#bank-sortable').sortable({
            handle: '.drag-handle',
            cursor: 'grabbing',
            axis: 'y',
            helper: function (e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function (index) {
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            },
            update: function () {
                var sortedIDs = $(this).sortable('toArray', {attribute: 'data-id'});

                $(this).children('tr').each(function (index) {
                    $(this).find('.row-number').text(index + 1);
                });

                $.ajax({
                    url: '{{route('admin.bank.sort')}}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({order: sortedIDs}),
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            text: 'با موفقیت انجام شد',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Failed to update ', error);
                    }
                });
            }
        });
    });
</script>
@include('admin._layouts.blocks.utils.confirmDelete')
@endpush
