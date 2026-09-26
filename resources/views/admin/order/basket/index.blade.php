@extends('admin._layouts.master')

@section('title')
    سبدهای خرید
@stop

@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap d-flex justify-content-between align-items-center">
                    <h3 class="fw-bolder mb-0">
                        سبدهای خرید
                    </h3>
                    <ul class="list-inline align-items-center m-0">
                        <li class="list-inline-item mx-0">
                            <a data-bs-target="#searchModal" data-bs-toggle="modal"
                               class="btn my-2 btn-custom rounded-custom d-flex align-items-center">
                                <i class="bi bi-search d-flex my-0 me-2"></i>
                                جستجوی سبدها
                            </a>
                        </li>
                    </ul>
                    <ul class="list-inline align-items-center m-0">
                        <li class="list-inline-item mx-0">
                            <a data-bs-target="#excelModal" data-bs-toggle="modal"
                               class="btn ms-2 my-2 btn-custom-b rounded-custom d-flex align-items-center w-fit">
                                <i class="bi bi-file-excel d-flex my-0 me-2"></i>
                                دانلود خروجی اکسل
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card-block row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table fw-medium text-start align-middle border-custom mb-0 text-light">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>کاربر</th>
                                    <th>تعداد اقلام</th>
                                    <th>مجموع مبلغ</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>آخرین تغییر</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($baskets as $key => $basket)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ @$basket->user->full_name ?? 'کاربر ناشناس' }}</td>
                                        <td>{{ $basket->items->count() }}</td>
                                        <td>{{ number_format($basket->final_price) }} تومان</td>
                                        <td>{{ jdate('H:i - Y/m/d',$basket->created_at->timestamp) }}</td>
                                        <td>{{ jdate('H:i - Y/m/d',$basket->updated_at->timestamp) }}</td>
                                        <td>
                                            <a href="{{ route('admin.basket.detail', ['id' => $basket->id]) }}"
                                               class="d-flex align-items-center justify-content-center"
                                               data-bs-toggle="tooltip" data-bs-title="مشاهده جزئیات">
                                                <i class="bi bi-eye color-custom2 fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            سبد فعالی یافت نشد.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @component("admin.components.pagination.default")
                        @slot("paginator", $baskets)
                    @endcomponent
                </div>
            </div>
        </div>
        @push("scripts")
            <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
            <script src="{{ asset('assets/admin/js/vue-select.js') }}"></script>
            <script src="{{ asset('assets/admin/js/axios.min.js') }}"></script>
            <script src="{{ asset('assets/admin/js/bootstrap-datepicker.min.js') }}"></script>
            <script src="{{ asset('assets/admin/js/bootstrap-datepicker.fa.min.js') }}"></script>

            <script>
                if ($.fn.datepicker && $.fn.datepicker.noConflict) {
                    var datepickerReserver = $.fn.datepicker.noConflict();
                    $.fn.bootstrapDP = datepickerReserver;
                }
            </script>
        @endpush
        @include("admin.order.basket.search")
        @include('admin.order.basket.excel')
    </div>

@endsection
