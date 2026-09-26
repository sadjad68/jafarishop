@extends('admin._layouts.master')

@section('title')
    جزئیات سفارش #{{ $data->id }}
@stop

@section('content')
@php
    $address = json_decode(@$data->address, true);
    $paymentBadgeClass = $data->order_status === 'wait_for_verification'
        ? 'payment-status-awaiting-verification'
        : 'bg-label-' . (@$data->status['badge'] ?? 'secondary');
    $bankTracking = @$data->bank_tracking_code
        ?: (json_decode(@$data->transaction_info, true)['post']['transactionId'] ?? '—');
@endphp

<div class="body d-flex py-3">
    <div class="container-fluid admin-order-page">
        <div class="admin-order-page__back mb-3">
            <a href="{{ route('admin.order.index') }}" class="admin-order-page__back-link">
                <i class="bi bi-arrow-right"></i>
                بازگشت به لیست سفارش‌ها
            </a>
        </div>

        @include('admin.order.order._partials.summary-hero', ['data' => $data])

        @if($data->order_status != 'paying')
            <div class="admin-order-actions">
                <a href="{{ route('admin.order.factor', ['id' => $data->id]) }}"
                   class="btn btn-custom rounded-custom"
                   target="_blank">
                    <i class="bi bi-printer"></i>
                    نسخه قابل چاپ
                </a>
                <a href="{{ route('admin.order.post-label', ['id' => $data->id]) }}"
                   class="btn btn-custom-b rounded-custom"
                   target="_blank">
                    <i class="bi bi-tag"></i>
                    لیبل پستی
                </a>
            </div>
        @endif

        <div class="admin-order-grid">
            @include('admin.order.order._partials.info-section', [
                'icon' => 'bi-person-badge',
                'title' => 'اطلاعات کاربر',
                'rows' => array_filter([
                    ['icon' => 'bi-person', 'label' => 'نام کاربر', 'value' => e(@$data->user->full_name ?? '—')],
                    ['icon' => 'bi-hash', 'label' => 'کد کاربر', 'value' => '<span class="font-num-r">' . e(@$data->user->id) . '</span>'],
                    ['icon' => 'bi-person-check', 'label' => 'نام گیرنده', 'value' => e($data->receiptor_full_name ?? '—')],
                    ['icon' => 'bi-telephone', 'label' => 'تلفن گیرنده', 'value' => '<span class="font-num-r" dir="ltr">' . e($address['receiptor_mobile'] ?? 'آدرس نادرست') . '</span>'],
                    ['icon' => 'bi-phone', 'label' => 'شماره همراه کاربر', 'value' => '<span class="font-num-r" dir="ltr">' . e(@$data->user->mobile) . '</span>'],
                    ['icon' => 'bi-mailbox', 'label' => 'کد پستی', 'value' => '<span class="font-num-r">' . e($address['postal_code'] ?? 'آدرس نادرست') . '</span>'],
                    ['icon' => 'bi-geo-alt', 'label' => 'آدرس', 'value' => e($address ? ($address['state'] . ' ' . $address['city'] . ' ' . $address['address']) : 'آدرس نادرست'), 'wide' => true],
                    !empty($data->torob_clid) ? [
                        'icon' => 'bi-link-45deg',
                        'label' => 'منبع ورود',
                        'value' => '<span class="badge bg-label-success">ترب</span> <span class="text-muted small font-num-r" dir="ltr">' . e($data->torob_clid) . '</span>',
                    ] : null,
                ]),
            ])

            @php
                $invoiceRows = [
                    ['icon' => 'bi-receipt', 'label' => 'شماره سفارش', 'value' => '<span class="font-num-r">#' . e($data->id) . '</span>'],
                    ['icon' => 'bi-truck', 'label' => 'روش ارسال', 'value' => e(@$data->shipping_method->title ?: 'ندارد')],
                    ['icon' => 'bi-bank', 'label' => 'درگاه پرداخت', 'value' => e(@$data->bank->title ?: 'ندارد')],
                    ['icon' => 'bi-upc-scan', 'label' => 'کد پیگیری درگاه', 'value' => '<span class="font-num-r">' . e($bankTracking) . '</span>'],
                    ['icon' => 'bi-basket', 'label' => 'قیمت کالاها', 'value' => '<span class="font-num-r">' . number_format($data->original_goods_price) . ' تومان</span>'],
                    [
                        'icon' => 'bi-truck-flatbed',
                        'label' => 'هزینه ارسال',
                        'value' => e($data->shipping_name) . ' <span class="badge bg-label-' . e(@$data->freight_balance_name['badge']) . '">' . e(@$data->freight_balance_name['title']) . '</span>',
                    ],
                    [
                        'icon' => 'bi-percent',
                        'label' => 'کد تخفیف / مبلغ',
                        'value' => $data->discount_id
                            ? e($data->discount->title) . ' / <span class="font-num-r">' . number_format(intval($data->discount_price)) . ' تومان</span>'
                            : 'ندارد',
                    ],
                    [
                        'icon' => 'bi-calculator',
                        'label' => 'مالیات بر ارزش افزوده',
                        'value' => intval($data->current_tax) != 0
                            ? '<span class="font-num-r">' . number_format(intval($data->tax_price)) . ' تومان (' . e($data->current_tax) . '٪)</span>'
                            : 'ندارد',
                    ],
                ];

                if (intval($data->gateway_tariff) > 0) {
                    $invoiceRows[] = [
                        'icon' => 'bi-credit-card-2-front',
                        'label' => 'تعرفه درگاه',
                        'value' => '<span class="badge bg-dark">' . intval($data->gateway_tariff) . '٪</span> <span class="font-num-r">' . number_format(intval($data->gateway_tariff_price)) . ' تومان</span>',
                    ];
                }

                $invoiceRows[] = [
                    'icon' => 'bi-wallet2',
                    'label' => 'مبلغ پرداختی',
                    'value' => '<strong class="font-num-r text-primary">' . number_format(intval($data->payment_price)) . ' تومان</strong>',
                ];

                if (intval($data->deposit_price) != 0) {
                    $invoiceRows[] = ['icon' => 'bi-piggy-bank', 'label' => 'مبلغ بیعانه', 'value' => '<span class="font-num-r">' . number_format(intval($data->deposit_price)) . ' تومان</span>'];
                    $invoiceRows[] = ['icon' => 'bi-cash-stack', 'label' => 'مبلغ باقی‌مانده', 'value' => '<span class="font-num-r">' . number_format(intval($data->remaining_price)) . ' تومان</span>'];
                }

                if (intval($data->deposit_price) != 0 || @$data->bank->bank_type === 'cardtocard' || count($data->images) > 0) {
                    $receiptHtml = '<div class="admin-order-receipts">';
                    foreach ($data->images as $image) {
                        if (strtolower(pathinfo($image->file, PATHINFO_EXTENSION)) === 'pdf') {
                            $receiptHtml .= '<a href="' . e($image->file_asset) . '" target="_blank" class="btn btn-outline-secondary btn-sm rounded-custom"><i class="bi bi-file-earmark-pdf"></i> PDF</a>';
                        } else {
                            $receiptHtml .= '<a href="' . e($image->file_asset) . '" target="_blank" class="admin-order-receipts__thumb"><img src="' . e($image->file_asset) . '" alt="فیش" width="52" height="52"></a>';
                        }
                    }
                    if ($data->order_status == 'wait_for_verification') {
                        $receiptHtml .= '<div class="admin-order-receipts__actions">';
                        $receiptHtml .= '<a href="' . route('admin.order.image-accept', ['id' => $data->id]) . '" class="btn btn-success btn-sm rounded-custom"><i class="bi bi-check-lg"></i> تایید</a>';
                        $receiptHtml .= '<a href="' . route('admin.order.image-decline', ['id' => $data->id]) . '" class="btn btn-danger btn-sm rounded-custom"><i class="bi bi-x-lg"></i> رد</a>';
                        $receiptHtml .= '</div>';
                    }
                    $receiptHtml .= '</div>';
                    $invoiceRows[] = ['icon' => 'bi-image', 'label' => 'فیش‌های واریزی', 'value' => $receiptHtml, 'wide' => true];
                }

                $invoiceRows[] = [
                    'icon' => 'bi-shield-check',
                    'label' => 'وضعیت پرداخت',
                    'value' => '<span class="badge ' . $paymentBadgeClass . '">' . e(@$data->status['title']) . '</span>',
                ];
                $invoiceRows[] = [
                    'icon' => 'bi-envelope-check',
                    'label' => 'کد پیگیری پستی',
                    'value' => '<span class="badge bg-label-secondary font-num-r">' . e($data->post_code ?? '—') . '</span>',
                ];

                if ($data->bijak_image_asset) {
                    $invoiceRows[] = [
                        'icon' => 'bi-file-earmark-image',
                        'label' => 'تصویر بیجک',
                        'value' => '<a href="' . e($data->bijak_image_asset) . '" target="_blank" class="admin-order-receipts__thumb"><img src="' . e($data->bijak_image_asset) . '" alt="بیجک" width="52" height="52"></a>',
                    ];
                }
            @endphp

            @include('admin.order.order._partials.info-section', [
                'icon' => 'bi-receipt-cutoff',
                'title' => 'اطلاعات فاکتور',
                'rows' => $invoiceRows,
            ])
        </div>

        @php
            $showPostCodeForm = @$data->shipping_method->type !== 'chapar' && @$data->order_status == 'paid';
            $showBijakForm = @$data->order_status == 'paid' && Auth::user()?->hasAdminPermission('admin.order.bijak');
            $orderFormsCount = 1 + ($showPostCodeForm ? 1 : 0) + ($showBijakForm ? 1 : 0);
            $orderFormsGridClass = $orderFormsCount === 1
                ? 'admin-order-forms-grid--single'
                : ($orderFormsCount === 3 ? 'admin-order-forms-grid--triple' : '');
        @endphp
        <div class="admin-order-forms-grid {{ $orderFormsGridClass }}">
            @if($showPostCodeForm)
                <div class="admin-order-form-card">
                    <div class="admin-order-form-card__head">
                        <i class="bi bi-send"></i>
                        <span>ارسال کد پیگیری پستی</span>
                    </div>
                    <form action="{{ route('admin.order.post-code') }}" method="POST" class="admin-order-form-card__body">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $data->id }}">
                        <div class="admin-order-form-card__fields">
                            <x-cms-input
                                name="post_code"
                                label="کد پیگیری پستی"
                                :validations="[]"
                                type="text"
                                :valueData="@$data"
                            />
                            <button type="submit" class="btn btn-custom rounded-custom w-100">
                                <i class="bi bi-chat-dots"></i>
                                ارسال پیامک
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if($showBijakForm)
                <div class="admin-order-form-card">
                    <div class="admin-order-form-card__head">
                        <i class="bi bi-file-earmark-image"></i>
                        <span>آپلود تصویر بیجک</span>
                    </div>
                    <form action="{{ route('admin.order.bijak', ['id' => $data->id]) }}" method="POST" enctype="multipart/form-data" class="admin-order-form-card__body">
                        @csrf
                        <div class="admin-order-form-card__fields">
                            @if($data->bijak_image_asset)
                                <a href="{{ $data->bijak_image_asset }}" target="_blank" class="admin-order-bijak-preview">
                                    <img src="{{ $data->bijak_image_asset }}" alt="بیجک سفارش #{{ $data->id }}">
                                    <span>مشاهده تصویر فعلی</span>
                                </a>
                            @endif
                            <div>
                                <label class="admin-label" for="bijak_image">{{ $data->bijak_image_asset ? 'جایگزینی تصویر' : 'انتخاب تصویر' }}</label>
                                <input
                                    type="file"
                                    id="bijak_image"
                                    name="bijak_image"
                                    class="form-control admin-input"
                                    accept="image/jpeg,image/png,image/webp,image/jpg"
                                    required
                                >
                                @error('bijak_image')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                                <small class="text-muted d-block mt-1">jpg، png یا webp تا ۲ مگابایت</small>
                            </div>
                            <button type="submit" class="btn btn-custom rounded-custom w-100">
                                <i class="bi bi-cloud-arrow-up"></i>
                                {{ $data->bijak_image_asset ? 'به‌روزرسانی بیجک' : 'ذخیره بیجک' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="admin-order-form-card">
                <div class="admin-order-form-card__head">
                    <i class="bi bi-truck"></i>
                    <span>تغییر وضعیت ارسال</span>
                </div>
                <form action="{{ route('admin.order.change-shipping-status', ['id' => $data->id]) }}" method="POST" class="admin-order-form-card__body">
                    @csrf
                    <div class="admin-order-form-card__fields">
                        <div>
                            <label class="admin-label">وضعیت ارسال</label>
                            <select name="shipping_status_id" class="form-select rounded-custom">
                                @foreach($shipping_statuses as $shipping_status)
                                    <option value="{{ $shipping_status->id }}" @selected($data->shipping_status_id == $shipping_status->id)>
                                        {{ $shipping_status->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-custom rounded-custom w-100">
                            <i class="bi bi-check2"></i>
                            ذخیره وضعیت
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(@$data->user_description)
            <div class="admin-order-section admin-order-section--full mt-3">
                <div class="admin-order-section__head">
                    <span class="admin-order-section__icon"><i class="bi bi-chat-left-text"></i></span>
                    <h3 class="admin-order-section__title">توضیحات کاربر</h3>
                </div>
                <div class="admin-order-section__body admin-order-section__body--note">
                    {!! $data->user_description !!}
                </div>
            </div>
        @endif

        @include('admin.order.order._partials.items-table', ['data' => $data])
    </div>
</div>
@stop

@push('scripts')
    <script src="{{ asset('assets/admin/js/vue.js') }}"></script>
    <script src="{{ asset('assets/admin/js/validations.js') }}"></script>
@endpush
