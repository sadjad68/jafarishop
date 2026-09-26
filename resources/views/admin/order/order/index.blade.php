@extends('admin._layouts.master')

@section('title')
    سفارش‌ها
@stop

@section('content')
    <div class="body d-flex py-3">
        <div class="container-fluid">
            <div class="page-header">
                <div class="card-header py-3 no-bg bg-transparent border-0 px-0 flex-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between w-100 gap-3">
                        <div>
                            <h3 class="fw-bolder mb-1">سفارش‌ها</h3>
                            @if($orders->total() > 0)
                                <p class="admin-order-list__subtitle mb-0">
                                    <span class="font-num-r">{{ number_format($orders->total()) }}</span> سفارش ثبت‌شده
                                </p>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @component('admin.components.video-button')
                                @slot('type', 'orders')
                            @endcomponent
                            @component('admin.components.video-button')
                                @slot('text', 'راهنمای خرید مشتری')
                                @slot('type', 'buy_guide')
                            @endcomponent
                            <a data-bs-target="#searchModal" data-bs-toggle="modal"
                               class="btn btn-custom rounded-custom d-inline-flex align-items-center">
                                <i class="bi bi-search me-2"></i>
                                جستجوی پیشرفته
                            </a>
                            <a data-bs-target="#excelModal" data-bs-toggle="modal"
                               class="btn btn-custom-b rounded-custom d-inline-flex align-items-center">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                                خروجی اکسل
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-block row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="myDataTable" class="table align-middle border-custom mb-0 admin-order-list__table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>شماره سفارش</th>
                                <th>نام کاربر</th>
                                <th>مبلغ پرداختی</th>
                                <th>روش ارسال</th>
                                <th style="width: 10%;">تاریخ</th>
                                <th>روش پرداخت</th>
                                <th>وضعیت پرداخت</th>
                                <th>وضعیت سفارش</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $key => $row)
                                <tr>
                                    <td><span class="font-num-r text-muted">{{ $key + 1 }}</span></td>
                                    <td>
                                        <span class="admin-order-list__id font-num-r">#{{ $row['id'] }}</span>
                                        @if(!empty($row->torob_clid))
                                            <span class="badge bg-label-success ms-1">ترب</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="admin-order-list__user">{{ @$row->user->full_name ?: '—' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-primary font-num-r">
                                            {{ number_format(@$row->payment_price) }} تومان
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ @$row->shipping_method->title ?: '—' }}</span>
                                    </td>
                                    <td>
                                        <span class="admin-order-list__date font-num-r">{{ @$row->date }}</span>
                                        @if(@$row->time)
                                            <span class="admin-order-list__time font-num-r d-block text-muted small">{{ @$row->time }}</span>
                                        @endif
                                    </td>
                                    <td>{{ @$row->bank->title ?: '—' }}</td>
                                    <td>
                                        <span class="badge {{ $row->order_status === 'wait_for_verification' ? 'payment-status-awaiting-verification' : 'bg-label-' . @$row->status['badge'] }}">
                                            {{ @$row->status['title'] }}
                                        </span>
                                        {!! $row->hasReturnItem() !!}
                                    </td>
                                    <td>
                                        @if(!in_array($row->order_status, ['paying', 'unpaid']))
                                            <span class="admin-order-badge admin-order-badge--sm"
                                                  style="--badge-color: {{ @$row->shipping_status->color ?? '#6c757d' }}">
                                                {{ @$row->shipping_status->title }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a class="d-flex align-items-center justify-content-center"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="جزئیات"
                                               href="{{ route('admin.order.detail', ['id' => $row->id]) }}">
                                                <i class="bi bi-eye color-custom2 fs-5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="admin-order-list__empty">
                                        <i class="bi bi-inbox"></i>
                                        <p>سفارشی یافت نشد.</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @component('admin.components.pagination.default')
                        @slot('paginator', $orders)
                    @endcomponent
                </div>
            </div>
        </div>

        @stack('modals')
    </div>

    @include('admin.order.order.search')
    @include('admin.order.order.excel')
@endsection

@push('scripts')
    @include('admin._layouts.blocks.utils.confirmDelete')
@endpush
